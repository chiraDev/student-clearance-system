<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Create this view to show the login form
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $this->redirectBasedOnRole(Auth::user());
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->is_super_admin) {
            return redirect()->route('users.import-form');
        }
        if ($user->is_management) {
            switch ($user->dep_id) {
                case 4:
                    return redirect()->route('ocus.ocus'); 
                case 5:
                     return redirect()->route('it-division');
                case 6:
                    return redirect()->route('logofficer.log'); 
                case 8:
                     return redirect()->route('cadetmess.cadetmess'); 
                case 9:
                     return redirect()->route('publication.publication');
                case 10:
                    return redirect()->route('sods.sods');
                case 11:
                     return redirect()->route('tso.tso');
                case 12:
                    return redirect()->route('library.library');
                case 13:
                    return redirect()->route('accsec.accsec');
                case 14:
                    return redirect()->route('helpdesk.helpdesk');
                case 15:
                    return redirect()->route('enlistment.enlistment');

                case 3:   
                    return redirect()->route('vc.vc');
                case 20:   
                    return redirect()->route('vc.vc');
                case 32:   
                    return redirect()->route('vc.vc');
                case 33:   
                    return redirect()->route('vc.vc');
                case 34:   
                    return redirect()->route('vc.vc');
                case 35:   
                    return redirect()->route('vc.vc');
                case 36:   
                    return redirect()->route('vc.vc');
                case 37:   
                    return redirect()->route('vc.vc');
                case 38:   
                    return redirect()->route('vc.vc');
                case 39:   
                    return redirect()->route('vc.vc');
                case 40:   
                    return redirect()->route('vc.vc');       
    
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['email' => 'Unauthorized access.']);
            }
        }

        if ($user->is_student) {
            return redirect()->route('student.dashboard');
        }

        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Unauthorized access.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
