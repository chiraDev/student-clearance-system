@extends('components.sidebar.sidebar')

@section('content')
<style>
    /* Typography and Spacing */
    .department-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        font-size: 2rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-align: center;
    }

    .total-requests {
        font-size: 1.5rem;
        color: #7f8c8d;
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 500;
    }

    .container {
    padding: 1rem; /* Decrease padding for reduced height */
    max-width: 90%; /* Decrease container size */
    margin: 0 auto;
    height: auto; /* Allow container height to adjust dynamically */
}

    /* Filter and Search Section */
    .filter-search-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e0e0e0 100%);
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 2.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .filter-search-section .form-group {
        margin-bottom: 0;
    }

    .filter-search-section .form-check {
        margin-right: 1rem;
        display: flex;
        align-items: center;
    }

    .filter-search-section .form-check-label {
        margin-left: 0.5rem;
    }

    .filter-search-section button,
    .filter-search-section a {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
    }

   
   /* List Layout */
.application-list {
    border-radius: 10px;
    border: 1px solid #1e00ff; /* Light blue border */
    display: flex;
    flex-direction: column; /* Stack items vertically */
    gap: 1.5rem;
    padding: 0;
    margin: 0;
}

.application-item {
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    width: 100%; /* Ensure each item takes full width */
}

.application-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

    .application-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .application-header h5 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .status-badge {
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .status-approved {
        background-color: #27ae60;
        color: #ffffff;
    }

    .status-rejected {
        background-color: #c0392b;
        color: #ffffff;
    }

    /* Application Details */
    .application-details p {
        margin: 0.5rem 0;
        color: #34495e;
        font-size: 0.95rem;
    }

    .application-details strong {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Form Container */


/* Form Elements */
.form-group {
    margin-bottom: 1.5rem;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: #495057;
    transition: border-color 0.3s ease-in-out;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

/* Checkboxes */
.form-check-label {
    margin-bottom: 0;
    font-size: 1rem;
    color: #34495e;
}

.form-check-input {
    margin-right: 0.5rem;
}

/* Form container with a border and padding */
.form-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ddd; /* Light grey border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Space inside the border */
    background-color: #f9f9f9; /* Light background color for contrast */
}
/* Custom checkbox style */
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #007bff; /* Primary color when checked */
    border-color: #007bff; /* Border color when checked */
}

.custom-checkbox .custom-control-label::before {
    background-color: #fff; /* Background color when unchecked */
    border: 1px solid #ddd; /* Light grey border */
}

.custom-checkbox .custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25); /* Focus state shadow */
}

/* Aligns the checkboxes to the left */
.checkbox-group {
    display: flex;
    align-items: center;
    border: 1px solid #ddd; /* Border around the checkbox group */
    border-radius: 5px; /* Rounded corners */
    padding: 5px; /* Space inside the border */
    background-color: #fff; /* White background color for contrast */
}

/* Ensures the checkboxes have some space between them */
.checkbox-group .form-check {
    margin-right: 1rem;
}

/* Styling for form-check label */
.custom-control-label {
    margin-bottom: 0; /* Remove bottom margin for labels */
    font-weight: 500; /* Slightly bolder text */
}

/* Buttons */
.btn {
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem; /* Adjusted font size for consistency */
    transition: background-color 0.3s ease-in-out;
}

.btn-primary {
    background-color: #3498db; /* Blue color */
    color: #ffffff;
    border: none;
}

.btn-primary:hover {
    background-color: #2980b9; /* Darker blue on hover */
}

.btn-secondary {
    background-color: #e0e0e0; /* Light gray for the reset button */
    color: #34495e;
    border: none;
}

.btn-secondary:hover {
    background-color: #b0b0b0; /* Darker gray on hover */
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-group {
        margin-bottom: 1rem;
    }

    .btn {
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-primary {
        margin-right: 0;
    }

    .btn-secondary {
        margin-left: 0;
    }
}

    /* Buttons */
   /* Buttons */
.button-group {
    margin-top: 1.5rem;
    display: flex;
    justify-content: space-between;
    gap: 0.75rem; /* Space between buttons */
}

  /* Buttons */
.button-group {
    margin-top: 1.5rem;
    display: flex;
    gap: 0.5rem; /* Reduce the gap between buttons */
   
   
}

.button-group .btn {
    width: 100px; /* Consistent width */
    height: 40px; /* Consistent height */
    padding: 0.75rem; /* Consistent padding */
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
    text-align: center;
    font-size: 0.675rem; /* Decreased font size */
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-approve {
    background-color: #2f00ff; /* Blue color */
    color: #ffffff;
}

.btn-approve:hover {
    background-color: #2980b9; /* Slightly darker blue */
}

.btn-decline {
    background-color: #eb081f; /* Red color */
    color: #ffffff;
}

.btn-decline:hover {
    background-color: #c62828; /* Darker red */
}

.btn-show-more {
    background-color: #11a301; /* Green color */
    color: #ffffff;
}

.btn-show-more:hover {
    background-color: #27ae60; /* Slightly darker green */
}

/* Ensures the form elements are aligned correctly */
.form-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Aligns the checkboxes to the left */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* Ensures the checkboxes have some space between them */
.checkbox-group .form-check {
    margin-right: 1rem;
}

/* Center the search bar */
.search-bar {
    flex: 1;
    max-width: 400px; /* Adjust the max-width as needed */
}

/* Aligns the buttons to the right */
.button-group {
    display: flex;
    align-items: center;
}

/* Adds spacing between buttons */
.button-group .btn {
    margin-left: 0.5rem;
}

.warning-message {
        font-size: 0.9em;
        color: #dc3545;
        margin-top: 5px;
    }

/* Responsive Design */
@media (max-width: 768px) {
    .button-group {
        flex-direction: column;
        gap: 0.75rem;
    }

    .button-group .btn {
        width: 100%;
    }
}



</style>


<div class="container">
    <h1 class="department-title">{{ auth()->user()->department->dep_name }} Department</h1>

    <form action="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}" method="GET" class="form-container">
        <div class="checkbox-group">
            <div class="custom-control custom-checkbox form-check-inline">
                <input type="checkbox" name="approved" class="custom-control-input" id="approvedCheckbox" {{ request('approved') ? 'checked' : '' }}>
                <label class="custom-control-label" for="approvedCheckbox">Approved</label>
            </div>
            <div class="custom-control custom-checkbox form-check-inline">
                <input type="checkbox" name="rejected" class="custom-control-input" id="rejectedCheckbox" {{ request('rejected') ? 'checked' : '' }}>
                <label class="custom-control-label" for="rejectedCheckbox">Rejected</label>
            </div>
        </div>
        <input type="text" name="search" class="form-control search-bar" placeholder="Search by Name, Application ID, or Registration Number" value="{{ request('search') }}">
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    
    <h2 class="total-requests">Total Requests: {{ $totalRequests }}</h2>

    <!-- Application List -->
    <ul class="application-list">
        @forelse($applicationStatuses as $status)
            <li class="application-item">
                <div class="application-header">
                    <h5>Application #{{ $status->application_id }}</h5>
                    @if(auth()->user()->dep_id != 14)
                        @php
                            $badgeClass = $status->status === 'APPROVED' ? 'status-approved' : 'status-rejected';
                        @endphp
                        <span class="status-badge {{ $badgeClass }}">{{ $status->status }}</span>
                    @endif
                </div>
                
                <div class="application-details">
                    <p><strong>User:</strong> {{ $status->application->user->user_name }}</p>
                    <p><strong>Registration Number:</strong> {{ $status->application->user->reg_no }}</p>
                    @if(auth()->user()->dep_id != 14)
                        @if($status->status === 'REJECTED')
                            <p><strong>Reason:</strong> {{ $status->reason ?? 'N/A' }}</p>
                        @endif
                        <p><strong>Status:</strong> {{ $status->status }}</p>
                    @endif
                    <p><strong>Last Updated:</strong> {{ $status->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
                <div class="button-group">
                    @if(auth()->user()->dep_id != 14)
                        @if (!$isEnlistment || ($isEnlistment && $status->allOthersApproved))
                            <form action="{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => $status->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="APPROVED">
                                <button type="submit" class="btn btn-approve">Approve</button>
                                
                                @if(auth()->user()->dep_id == 4)
                                    <div class="warning-message mt-2 text-danger">
                                        <small><strong>Warning:</strong> Please check the KDU ID before giving approval.</small>
                                    </div>
                                @endif
                            </form>
                        @else
                            <div class="approval-container">
                                <button class="btn btn-approve" disabled>Approve</button>
                                <small class="text-danger">Departments are not completed</small>
                            </div>
                        @endif
                        
                        <button type="button" class="btn btn-decline" onclick="declineApplication('{{ $status->id }}')">Decline</button>
                    @endif

                    @php
                        $hideShowMoreButton = in_array(auth()->user()->dep_id, [3,31,32,33,34,35,36,37,38,39,40, 4, 5, 6, 7, 9, 10, 12, 13]);
                    @endphp

                    @if(!$hideShowMoreButton)
                        <a href="{{ route('student.dashboard') }}" class="btn btn-show-more">Show More</a>
                    @endif
                </div>
            </li>
        @empty
            <li class="application-item">
                <p class="text-center">No applications found.</p>
            </li>
        @endforelse
    </ul>
    
    @if(auth()->user()->dep_id != 14)
        <script>
        function declineApplication(statusId) {
            var reason = prompt("Please enter the reason for declining:");
            if (reason != null && reason != "") {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => ':statusId']) }}".replace(':statusId', statusId);
                
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);
        
                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
        
                var statusField = document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'status';
                statusField.value = 'REJECTED';
                form.appendChild(statusField);
        
                var reasonField = document.createElement('input');
                reasonField.type = 'hidden';
                reasonField.name = 'reason';
                reasonField.value = reason;
                form.appendChild(reasonField);
        
                document.body.appendChild(form);
                form.submit();
            }
        }
        </script>
    @endif
</div>
@endsection