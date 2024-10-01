@extends('components.sidebar.sidebar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/custome-style.css') }}">
<div class="container">

    <!-- Department Header -->
    <h1 class="department-title">{{ auth()->user()->department->dep_name }} Department</h1>
    <h2 class="total-requests">Total Requests: {{ $totalRequests }}</h2>

    <!-- Isolated Dropdown for Selecting Person -->
    <div class="dropdown-container">
        <form action="{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => $applicationStatuses->first()->id ?? '']) }}" method="POST" id="person-select-form">
            @csrf
            @method('PUT')
            <select name="rank" class="form-control" id="person-dropdown" onchange="updateRankValue(this.value)">
                <option value="">-- Select Person --</option>
                @foreach($ranks as $rank)
                    <option value="{{ $rank->person_name }}" 
                        {{ old('rank', $selectedRank) == $rank->person_name ? 'selected' : '' }}>
                        {{ $rank->person_name }} ({{ $rank->rank_name }})
                    </option>
                @endforeach
            </select>
        </form>
    </div>

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

                @if(auth()->user()->dep_id != 14)
                    @if (!$isEnlistment || ($isEnlistment && $status->allOthersApproved))
                        <form action="{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => $status->id]) }}" method="POST" onsubmit="return setPersonNameBeforeSubmit({{ $status->id }})">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="APPROVED">

                            <!-- Hidden rank input for submission -->
                            <input type="hidden" name="rank" id="hidden-rank-{{ $status->id }}">

                            <!-- Hidden person_name input for submission -->
                            <input type="hidden" name="person_name" id="hidden-person-name-{{ $status->id }}">

                            <button type="submit" class="btn btn-approve">Approve</button>

                            @if(auth()->user()->dep_id == 3)
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
    <script>
        // Function to set the rank and person name values for each approval form
        function updateRankValue(rank) {
            console.log('Rank value selected:', rank);

            // Set the rank and person name in all hidden inputs
            var hiddenRankInputs = document.querySelectorAll('input[name="rank"]');
            var hiddenPersonNameInputs = document.querySelectorAll('input[name="person_name"]');
            hiddenRankInputs.forEach(input => input.value = rank);
            hiddenPersonNameInputs.forEach(input => input.value = rank);

            // Optionally, store the selected rank in session storage to persist across page reloads
            sessionStorage.setItem('selectedRank', rank);
        }

        // Function to handle setting person_name before form submission
        function setPersonNameBeforeSubmit(statusId) {
            var personDropdown = document.getElementById('person-dropdown');
            var personName = personDropdown.value;

            console.log('Person name before submit for status ID', statusId, ':', personName);

            if (!personName) {
                alert('Please select a person before submitting.');
                return false; // Prevent form submission
            }

            // Update hidden inputs with the selected person name
            document.getElementById('hidden-person-name-' + statusId).value = personName;
            document.getElementById('hidden-rank-' + statusId).value = personName;
            
            console.log('Hidden person_name input updated with:', personName);

            return true; // Allow form submission
        }

        // On document load, retrieve the selected rank from session storage and set it in the dropdown
        document.addEventListener('DOMContentLoaded', function () {
            var selectedRank = sessionStorage.getItem('selectedRank');
            if (selectedRank) {
                document.getElementById('person-dropdown').value = selectedRank;
                updateRankValue(selectedRank);
            }
        });

        function declineApplication(statusId) {
            console.log("Decline button clicked for status ID:", statusId);
            var reason = prompt("Please enter the reason for declining:");

            if (reason != null && reason !== "") {
                console.log("Reason provided:", reason);

                var form = new FormData();
                form.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                form.append('_method', 'PUT');
                form.append('status', 'REJECTED');
                form.append('reason', reason);
                form.append('rank', document.getElementById('person-dropdown').value);
                form.append('person_name', document.getElementById('person-dropdown').value); // Ensure person_name is set

                fetch("{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => ':statusId']) }}".replace(':statusId', statusId), {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            } else {
                console.log("Decline action cancelled or no reason provided.");
            }
        }
    </script>
</div>
@endsection

