<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure this view exists to show the login form
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate the credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Redirect based on the authenticated user's role
        return $this->redirectBasedOnRole(Auth::user());
    }

    // Redirect users based on their roles
    protected function redirectBasedOnRole($user)
    {
        // Redirect for super admin
        if ($user->is_super_admin) {
            return redirect()->route('users.import-form');
        }


        // Redirect for management users
        if ($user->is_management) {
            switch ($user->dep_id) {
                case 3:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 31:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 32:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 33:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 34:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 35:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 36:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 37:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 38:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 39:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 40:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 4:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 5:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 6:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 7:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 8:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 9:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 10:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 11:
                        return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                 case 12:
                         return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 13:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 14:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 15:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);
                case 16:
                    return redirect()->route('Clearance.list', ['departmentId' => $user->dep_id]);

                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['email' => 'Unauthorized access.']);
            }
        }

        // Redirect for student users
        if ($user->is_student) {
            return redirect()->route('student.dashboard');
        }

        // Logout and redirect to login for unauthorized access
        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Unauthorized access.']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect('/login');
    }
}