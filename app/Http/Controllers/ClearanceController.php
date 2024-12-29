<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ClearanceController extends Controller
{
    public function index(Request $request, $departmentId)
    {
        // Fetch persons (ranks) for the dropdown based on the logged-in user's department
        $ranks = Rank::where('department_id', $departmentId)->get();
    
        // Get the selected rank from the request
        $selectedRank = $request->input('rank');
    
        // Query to fetch ApplicationStatus related to the department
        $query = ApplicationStatus::where('department_id', $departmentId);
    
        // Filter by approval status (approved or rejected)
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
    
        // Filter by selected person (rank)
        if ($request->filled('rank')) {
            $query->where('rank', $selectedRank);  // Filter by rank column in ApplicationStatus
        }
    
        // Get the total count of requests
        $totalRequests = $query->count();
    
        // Get the paginated results
        $applicationStatuses = $query->with(['application.studentInfo', 'application.user'])->paginate(10);

        // Load the related applications and users
        $applicationStatuses->load('application.user');
    
        // Check if the current department is Enlistment (assuming dep_id 16 is Enlistment)
        $isEnlistment = $departmentId == 16;
    
        // If it's Enlistment, check approval status for each application
        if ($isEnlistment) {
            foreach ($applicationStatuses as $status) {
                $status->allOthersApproved = $this->allOtherDepartmentsApproved($status->application_id, $departmentId);
            }
        }
    
        return view('Clearance.department', [
            'applicationStatuses' => $applicationStatuses,
            'totalRequests' => $totalRequests,
            'isEnlistment' => $isEnlistment,
            'departmentId' => $departmentId,
            'ranks' => $ranks,
            'selectedRank' => $selectedRank  // Pass the selected rank to the view
        ]);
    }
    
    // When creating or updating ApplicationStatus, include the person name
    public function updateStatus(Request $request, $departmentId, $statusId)
    {
        try {
            // Log the request and parameter values at the start
            Log::info('Update status request data: ', [
                'departmentId' => $departmentId,
                'statusId' => $statusId,
                'request' => $request->all()
            ]);
    
            // Fetch the ApplicationStatus to be updated
            $status = ApplicationStatus::findOrFail($statusId);
            Log::info('Fetched ApplicationStatus:', ['status' => $status]);
    
            // Check for department mismatch
            if ($status->department_id != $departmentId) {
                return redirect()->back()->with('error', 'Unauthorized action: Department ID mismatch.');
            }
    
            // Get the rank and validate it
            $personName = $request->input('rank');
            if (!$personName) {
                return redirect()->back()->with('error', 'Please select a person to approve or reject the application.');
            }
    
            Log::info('Selected person name:', ['personName' => $personName]);
    
            // Retrieve the rank_name from the Rank model based on the person_name
            $rank = Rank::where('person_name', $personName)->first();
            if (!$rank) {
                Log::warning('Invalid rank selected or not found:', ['personName' => $personName]);
                return redirect()->back()->with('error', 'Invalid rank selected.');
            }
    
            Log::info('Fetched Rank:', ['rank' => $rank]);
    
            // Validate the status value
            $statusValue = $request->input('status');
            if (!in_array($statusValue, ['APPROVED', 'REJECTED'])) {
                Log::warning('Invalid status value received:', ['statusValue' => $statusValue]);
                return redirect()->back()->with('error', 'Invalid status selected.');
            }
    
            // Log the data to be updated
            $data = [
                'status' => $statusValue,
                'rank' => $rank->rank_name, // Save rank_name
                'updated_by' => Auth::id(),
                'person_name' => $personName, // Save person_name
            ];
            Log::info('Data prepared for update:', ['data' => $data]);
    
            // Check if reason is provided for rejected status
            if ($statusValue === 'REJECTED') {
                $reason = $request->input('reason');
                if (empty($reason)) {
                    Log::warning('Rejection reason missing for rejected status.');
                    return redirect()->back()->with('error', 'Reason is required when rejecting an application.');
                }
                $data['reason'] = $reason;
            } else {
                $data['reason'] = null;
            }
    
            // Attempt to update the status
            $updated = $status->update($data);
            Log::info('Update operation result:', ['updated' => $updated]);
            Log::info('Updated status data:', $status->toArray());
    
            // If the update failed, log and return an error
            if (!$updated) {
                Log::error('Failed to update the application status in the database.');
                return redirect()->back()->with('error', 'Failed to update status. Please try again.');
            }
    
            Log::info('Status updated successfully.', ['statusId' => $statusId]);
    
            // Save the updated application (if necessary)
            $application = Application::findOrFail($status->application_id);
            $application->save();
            Log::info('Application saved successfully after status update.', ['application' => $application]);
    
            // Create a success message based on the status value
            $message = $statusValue === 'APPROVED'
                ? 'Application approved successfully.'
                : 'Application rejected successfully with reason: ' . $reason;
    
            // Redirect to the Clearance.list route with the departmentId
            return redirect()->route('Clearance.list', ['departmentId' => $departmentId])
                             ->with('success', $message);
    
        } catch (\Exception $e) {
            // Log any exceptions with the stack trace for deeper insights
            Log::error('Error updating application status:', [
                'message' => $e->getMessage(),
                'stackTrace' => $e->getTraceAsString()
            ]);
    
            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while updating the status. Please try again.');
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