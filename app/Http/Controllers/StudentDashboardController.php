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
    }
}
