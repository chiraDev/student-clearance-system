<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentInfo;
use App\Models\Application;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Log;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ensure the user is authenticated
        if (!$user) {
            return redirect()->route('login')->withErrors(['message' => 'Please log in to access the dashboard.']);
        }

        // Check if dep_id is set
        if (is_null($user->dep_id)) {
            Log::error("User with ID {$user->id} does not have a dep_id assigned.");
            return redirect()->route('home')->with('error', 'Your department information is missing. Please contact support.');
        }

        // Retrieve the student's information along with the related faculty data
        $studentInfo = StudentInfo::with('faculty')->where('user_id', $user->id)->first();

        // Check if StudentInfo exists
        if (!$studentInfo) {
            Log::warning("StudentInfo not found for user_id: {$user->id}");
            return redirect()->route('home')->with('error', 'Student information not found.');
        }

        // Retrieve the application for the student
        $application = Application::where('student_id', $studentInfo->id)->first();

        // Retrieve all department statuses for this student's application
        $departmentStatuses = [];
        if ($application) {
            $departmentStatuses = ApplicationStatus::with('department', 'updater')
                ->where('application_id', $application->id)
                ->get();
        }

        // Retrieve total requests
        $totalRequests = $departmentStatuses->count();

        // Pass the necessary data to the view
        return view('student.dashboard', [
            'user' => $user,
            'studentInfo' => $studentInfo,
            'application' => $application,
            'departmentStatuses' => $departmentStatuses,
            'totalRequests' => $totalRequests,
        ]);


        // Check if an application already exists for the student
        if (Application::where('student_id', $studentInfo->id)->exists()) {
            return redirect()->route('student.dashboard')->with('error', 'You have already submitted a clearance application.');
        }

        // Create a new application record
        $application = new Application();
        $application->student_id = $studentInfo->id;
        $application->user_name = $user->user_name; 
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
