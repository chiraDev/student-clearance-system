<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\StudentInfo;
use Illuminate\Support\Facades\DB;
use App\Models\User; 



class StudentDashboardController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Retrieve the student's information along with the related faculty data
        $studentInfo = StudentInfo::with('faculty')->where('user_id', $user->id)->first();

        // Retrieve the application for the student
        $application = Application::where('student_id', $studentInfo->id)->first();

        // Retrieve all department statuses for this student's application
        $departmentStatuses = [];
        if ($application) {
            $departmentStatuses = ApplicationStatus::with('department', 'updater')
                ->where('application_id', $application->id)
                ->get();
                //->slice(2);
        }

        // Pass the user, student information, application, and department statuses to the view
        return view('student.dashboard', compact('user', 'studentInfo', 'application', 'departmentStatuses'));
    }
    public function management()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Retrieve the student's information along with the related faculty data
        $studentInfo = StudentInfo::with('faculty')->where('user_id', $user->id)->first();

        // Retrieve the application for the student
        $application = Application::where('student_id', $studentInfo->id)->first();

        // Retrieve all department statuses for this student's application
        $departmentStatuses = [];
        if ($application) {
            $departmentStatuses = ApplicationStatus::with('department', 'updater')
                ->where('application_id', $application->id)
                ->get();
                //->slice(2);
        }

        // Pass the user, student information, application, and department statuses to the view
        return view('management.dashboard', compact('user', 'studentInfo', 'application', 'departmentStatuses'));
    }

    public function submitClearanceForm(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Retrieve the student's information based on the authenticated user's ID
        $studentInfo = StudentInfo::where('user_id', $user->id)->first();

        // Validate the request data (Add validation rules as needed)
        $request->validate([
            // Add your validation rules here
        ]);

        // Check if an application already exists for the student
        if (Application::where('student_id', $studentInfo->id)->exists()) {
            return redirect()->route('student.dashboard')->with('error', 'You have already submitted a clearance application.');
        }

        // Create a new application record
        $application = new Application();
        $application->student_id = $studentInfo->id;
        $application->application_status = 'PENDING'; // Default status
        $application->created_by = $user->id;
        $application->updated_by = $user->id;
        $application->save();

        // Retrieve the student's faculty ID
        $studentFacultyId = $studentInfo->faculty_id;

        // Retrieve all departments, excluding those with dep_id 1, 2, and 3
        $departments = DB::table('departments')
        ->whereNotIn('id', [1, 2, 3, 14]) // Exclude departments with ID 1, 2, and 3
        ->where(function($query) use ($studentFacultyId) {
            $query->whereNull('faculty_id') // Include general departments
                  ->orWhere('faculty_id', $studentFacultyId); // Include departments related to the student's faculty
        })
        ->get();

        // Create an application status record for each remaining department
        foreach ($departments as $department) {
            $applicationStatus = new ApplicationStatus();
            $applicationStatus->application_id = $application->id;
            $applicationStatus->department_id = $department->id;
            $applicationStatus->status = 'PENDING'; // Initial status
            $applicationStatus->reason = '';
            $applicationStatus->created_by = $user->id;
            $applicationStatus->updated_by = $user->id;
            $applicationStatus->save();
        }

        // Redirect back with a success message
        return redirect()->route('student.dashboard')->with('success', 'Clearance application submitted successfully.');
    }



}

