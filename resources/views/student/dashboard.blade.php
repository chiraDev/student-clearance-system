<head>
    <link rel="stylesheet" href="{{ asset('css/student-dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->

</head>

@extends('layouts.student')

<div class="navbar-container">
    @include('components.nav') <!-- Include the navbar here -->
    
</div>

@section('content')

<h1 class="dashboard-title">Student Dashboard</h1>
    <div> <!-- The main container -->
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">
                Logout
            </button>
        </form>
        <div class="card">
            <div class="card-header">Clearance Application</div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.submitClearanceForm') }}" onsubmit="disableSubmitButton(this)">
                    @csrf
                    
                    <div class="form-group">
                        <span class="user-name-label">{{ $user->user_name }}</span>
                    </div>

                    <div class="form-group">
                        <span class="form-value">{{ $studentInfo->student_reg_no }}</span>
                    </div>
            
                    <div class="form-group">
                        <span class="form-value">{{ $studentInfo->faculty->faculty_name }}</span>
                    </div>
            
                    <div class="form-group">
                        <span class="form-value">{{ ucwords(strtolower($studentInfo->student_type)) }}</span>
                    </div>
                    
                    <div class="button-container">
                        <button type="submit" class="btn btn-primary" id="submitButton" {{ $application ? 'disabled' : '' }}>
                            {{ $application ? 'Application Submitted' : 'Submit Clearance Form' }}
                        </button>
                        <br>
                            @if ($allApproved) <!-- Condition to check if all departments have approved -->
                                <a href="{{ route('student.downloadClearancePDF') }}" class="downloadButton" id="downloadButton">
                                    Download Clearance
                                </a>
                            @else
                                <button class="downloadButtonDisable" id="downloadButton" disabled>
                                    Download Clearance
                                </button>
                            @endif
                        </div>
                </form>
            </div>
        </div>
        
        @if ($application)
        <div class="card mt-4">
            <div class="card-header">Application Status by Department</div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Updated By</th>
                            <th>Rank</th> <!-- New Rank Column -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departmentStatuses as $status)
                            <tr>
                                <td>{{ $status->department->dep_name }}</td>
                                <td class="
                                    {{ $status->status === 'PENDING' ? 'status-pending' : '' }}
                                    {{ $status->status === 'REJECTED' ? 'status-rejected' : '' }}
                                    {{ $status->status === 'APPROVED' ? 'status-approved' : '' }}
                                    {{ $status->status === 'HIDDEN' ? 'status-hidden' : '' }}">
                                    <span class="status-box">
                                        {{ $status->status === 'HIDDEN' ? 'Status Hidden' : $status->status }}
                                    </span>
                                </td>
                                <td>{{ $status->reason }}</td>
                                <td>{{ $status->person_name ?? 'N/A' }}</td>
                                <td>{{ $status->rank ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

<script>
    function disableSubmitButton(form) {
        form.querySelector('#submitButton').disabled = true;
        form.querySelector('#submitButton').textContent = 'Submitting...';
    }
</script>


