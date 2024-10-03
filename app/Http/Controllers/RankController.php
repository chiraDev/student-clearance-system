<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rank; // Assuming you have a Rank model

class RankController extends Controller
{
    // Show the form for creating a new person
    public function create()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }

        $departmentId = Auth::user()->dep_id;
        return view('ranks.create', compact('departmentId')); // Return the view for adding a person
    }

    // Store a newly created person in storage
    public function store(Request $request)
    {
        // Validate the request data
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'rank_name' => 'required|string|max:255',
            'person_name' => 'required|string|max:255',
        ]);

        // Create a new rank entry
        Rank::create([
            'rank_name' => $request->rank_name,
            'person_name' => $request->person_name,
            'department_id' => Auth::user()->dep_id, // Set the department_id
        ]);

        // Redirect with success 
        return redirect()->route('ranks.create')->with('success', 'Person added successfully!');

    }
    public function getRanksByDepartment(Request $request)
    {
        $department_id = $request->input('department_id');
        $ranks = Rank::where('department_id', $department_id)->get();
        
        return response()->json($ranks);  // Return the ranks as JSON to the frontend
    }

    public function getDepartments()
    {
        $departments = Department::all();
        return view('your-view-file', compact('departments'));
    }
}
