<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ClearanceReportController;
use App\Http\Controllers\ClearanceRequestController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\RankController;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/student/download-clearance', [StudentDashboardController::class, 'downloadClearancePDF'])->name('student.downloadClearancePDF');


Route::group(['middleware' => ['auth']], function () {
    // Route to filter by person (rank) in a specific department
    Route::get('/clearance/{departmentId}/filter', [ClearanceController::class, 'index'])
        ->name('Clearance.filter');
});

Route::get('/departments', [RankController::class, 'getDepartments']);  // To load the page with departments
Route::post('/get-ranks', [RankController::class, 'getRanksByDepartment']);  // To fetch ranks based on department

    // Route to show the form (GET method)
    Route::get('/ranks/create', [RankController::class, 'create'])->name('ranks.create');

    // Route to handle form submission (POST method)
    Route::post('/ranks', [RankController::class, 'store'])->name('ranks.store');
// Forgot Password
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
//////////////////////////////////////////////

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});

/////student dashboard functions/////
Route::middleware('auth')->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::post('/student/submit-clearance-form', [StudentDashboardController::class, 'submitClearanceForm'])->name('student.submitClearanceForm');
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('im', [UserController::class, 'importForm'])->name('users.import-form');
Route::post('im', [UserController::class, 'import'])->name('users.import');
Route::post('/users/send-activation-emails', [UserController::class, 'sendActivationEmails'])->name('users.send-activation-emails');

// Route::middleware(['role:superadmin'])->group(function () {
//     Route::get('im', [UserController::class, 'importForm'])->name('users.import-form');
//     // Add the clearance.graph route here if it's also for super admins
//     Route::get('clearance/graph', [ClearanceReportController::class, 'showClearanceGraph'])->name('clearance.graph');
// });


 Route::middleware(['auth', 'role:management'])->group(function () {
    Route::get('/vc/dashboard', function() {
        return view('management.vc.vc');
    })->name('vc.vc');
     
    Route::get('/helpdesk/dashboard', function() {
        return view('management.helpdesk.helpdesk');
    })->name('helpdesk.helpdesk');
    
    Route::get('/enlistment/dashboard', function() {
        return view('management.enlistment.enlistment');
    })->name('enlistment.enlistment');
    
    Route::get('/sods/dashboard', function() {
        return view('management.sods.sods');
    })->name('sods.sods');
    
    Route::get('/ocus/dashboard', function() {
        return view('management.ocus.ocus');
    })->name('ocus.ocus');
    
    Route::get('/log/dashboard', function() {
        return view('management.logOfficer.log');
    })->name('logOfficer.log');
    
    Route::get('/accsec/dashboard', function() {
        return view('management.accsec.accsec');
    })->name('accsec.accsec');
    
    Route::get('/library/dashboard', function() {
        return view('management.library.library');
    })->name('library.library');
    
    
    Route::get('/arfoc/dashboard', function() {
        return view('management.arfoc.arfoc');
    })->name('arfoc.arfoc');

    Route::get('/cadetmess/dashboard', function() {
        return view('management.cadetmess.cadetmess');
    })->name('cadetmess.cadetmess');   

    Route::get('/publication/dashboard', function() {
        return view('management.publication.publication');
    })->name('publication.publication');  

    Route::get('/publication/tso', function() {
        return view('management.tso.tso');
    })->name('tso.tso'); 
    
 });

// Route::patch('/applications/{id}/status', [ClearanceRequestController::class, 'updateStatus'])->name('updateStatus');


// Dashboard Routes

// Clearance Routes
Route::get('/clearance/{departmentId}', [ClearanceController::class, 'index'])->name('Clearance.list');
Route::put('/clearance/{departmentId}/{statusId}', [ClearanceController::class, 'updateStatus'])->name('Clearance.update');
  
Route::get('/applications/filter', [ApplicationStatusController::class, 'filter'])->name('applications.filter');
Route::get('/management/dashboard', [StudentDashboardController::class, 'management'])->name('management.dashboard');


// Route::get('/student/download-clearance-pdf', [StudentDashboardController::class, 'downloadClearancePDF'])
//     ->name('student.downloadClearancePDF');

Route::get('/status-chart', [ClearanceReportController::class, 'index'])->name('status.chart');
Route::get('/duration-chart', [ClearanceReportController::class, 'duration'])->name('duration.chart');
Route::get('/user-profile', [ClearanceReportController::class, 'show'])->name('user.profile');
Route::put('/user/{id}', [ClearanceReportController::class, 'update'])->name('user.update');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/manage', [DepartmentController::class, 'index'])->name('departments.manage');
Route::post('/store', [DepartmentController::class, 'store'])->name('departments.store');
Route::post('/destroy', [DepartmentController::class, 'destroy'])->name('departments.destroy');
Route::get('/search', [DepartmentController::class, 'search'])->name('departments.search');

Route::get('/add', [DepartmentController::class, 'showAddStaffForm'])->name('departments.add.form');
Route::post('/add', [DepartmentController::class, 'addStaff'])->name('departments.add');
Route::get('/user', [DepartmentController::class, 'show'])->name('departments.profile');
Route::put('/userp/{id}', [DepartmentController::class, 'update'])->name('user.updateinfo');
