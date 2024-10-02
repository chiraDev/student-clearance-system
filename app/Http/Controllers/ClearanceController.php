<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationStatus;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class ClearanceController extends Controller
{
    public function index(Request $request, $departmentId)
    {
        $query = ApplicationStatus::where('department_id', $departmentId);

        // If department ID is 15, only show applications approved by all other departments except 1 and 2
        if ($departmentId == 15) {
            $query->whereHas('application', function ($q) use ($departmentId) {
                $q->whereDoesntHave('applicationStatuses', function ($sq) use ($departmentId) {
                    $sq->whereNotIn('department_id', [1, 2, $departmentId])
                       ->where('status', '!=', 'APPROVED');
                });
            });
        }

        // Filter by approval status
        if ($request->has('approved') && !$request->has('rejected')) {
            $query->where('status', 'APPROVED');
        } elseif ($request->has('rejected') && !$request->has('approved')) {
            $query->where('status', 'REJECTED');
        } elseif ($request->has('approved') && $request->has('rejected')) {
            $query->whereIn('status', ['APPROVED', 'REJECTED']);
        }

        // Search by name, application ID, or registration number
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('application.user', function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('reg_no', 'like', "%{$search}%");
            })->orWhere('application_id', 'like', "%{$search}%");
        }

        // Get the total count of requests
        $totalRequests = $query->count();

        // Get the paginated results
        $applicationStatuses = $query->with(['application.studentInfo', 'application.user'])->paginate(10);

        // Load the related applications and users
        $applicationStatuses->load('application.user');

        // Check if the current department is Enlistment (assuming dep_id 15 is Enlistment)
        $isEnlistment = $departmentId == 15;

        return view('Clearance.department', [
            'applicationStatuses' => $applicationStatuses,
            'totalRequests' => $totalRequests,
            'isEnlistment' => $isEnlistment,
            'departmentId' => $departmentId
        ]);
    }








    public function updateStatus(Request $request, $departmentId, $statusId)
    {
        try {
            $status = ApplicationStatus::findOrFail($statusId);

            if ($status->department_id != $departmentId) {
                return back()->with('error', 'Unauthorized action.');
            }

            // If it's Enlistment department, check if all other departments have approved
            if ($departmentId == 15 && $request->input('status') === 'APPROVED') {
                if (!$this->allOtherDepartmentsApproved($status->application_id, $departmentId)) {
                    return back()->with('error', 'All other departments must approve first.');
                }
            }

            $data = [
                'status' => $request->input('status'),
                'updated_by' => Auth::id()  // Updated here
            ];

            // If the status is REJECTED, include the reason
            if ($request->input('status') === 'REJECTED') {
                $data['reason'] = $request->input('reason');
            } else {
                // If the status is APPROVED, set the reason to null
                $data['reason'] = null;
            }

            $updated = $status->update($data);

            if (!$updated) {
                return back()->with('error', 'Failed to update status. Please try again.');
            }

            $message = $request->input('status') === 'APPROVED' 
                ? 'Application approved successfully.' 
                : 'Application rejected successfully.';

            return redirect()->route('Clearance.list', ['departmentId' => $departmentId])
                             ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the status.');
        }
    }








    private function allOtherDepartmentsApproved($applicationId, $currentDepartmentId)
    {
        $otherStatuses = ApplicationStatus::where('application_id', $applicationId)
            ->where('department_id', '!=', $currentDepartmentId)
            ->get();

        return $otherStatuses->every(function ($status) {
            return $status->status === 'APPROVED';
        });
    }
}
