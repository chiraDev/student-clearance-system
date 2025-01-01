@extends('layouts.management')

@section('content')
<link rel="stylesheet" href="{{ asset('css/custome-style.css') }}">
    <div>

    <!-- Sticky Department Header -->
    <div class="sticky-header">
    <!-- <h1 class="department-title">{{ auth()->user()->department->dep_name }}</h1> -->
    
        <div class="filter-container">
            
            <h2 class="total-requests">Total Requests: {{ $totalRequests }}</h2>
            <label><input type="checkbox" name="all_requests" id="allRequests" checked> All Requests</label>
            <label><input type="checkbox" name="approved_requests" id="approvedRequests"> Approved Requests</label>
            <label><input type="checkbox" name="rejected_requests" id="rejectedRequests"> Rejected Requests</label>
            <input type="text" class="search-input" placeholder="Search by Reg No" id="searchRegNo">
            <button class="submit-button" id="filterSubmit">Filter</button>
        </div>
    </div>

    <!-- Scrollable Panel -->
    <div class="container">   
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

                <!-- Application Details -->
                <div class="application-details">
                    <div class="detail-item"><strong>Reg. No:</strong> {{ $status->application->user->reg_no }}</div>
                    <div class="detail-item"><strong>Name:</strong> {{ $status->application->user->user_name }}</div>
                    <div class="detail-item"><strong>Tel. No:</strong> {{ $status->application->user->studentInfo->tel_no ?? 'N/A' }}</div>
                    <div class="detail-item"><strong>Bank</strong> {{ $status->application->user->studentInfo->bank ?? 'N/A' }}</div>
                    <div class="detail-item"><strong>Bank Acc No:</strong> {{ $status->application->user->studentInfo->account_number ?? 'N/A' }}</div>
                    @if(auth()->user()->dep_id != 14)
                        @if($status->status === 'REJECTED')
                        <div class="detail-item"><strong>Reason:</strong> {{ $status->reason ?? 'N/A' }}</div>
                        @endif
                        <div class="detail-item"><strong>Status:</strong> {{ $status->status }}</div>
                    @endif
                    <div class="detail-item"><strong>Last Updated:</strong> {{ $status->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <div class="button-group">
                <!-- PDF View Buttons on the Left -->
                <div class="pdf-view-buttons">
                    @if(in_array(auth()->user()->dep_id, [12, 25]))
                    <button type="button" class="btn btn-pdf" onclick="generatePdf('{{ $status->id }}')">Generate PDF</button>
                    @endif

                    @if(auth()->user()->dep_id == 13)
                    <button type="button" class="btn btn-hostel-pdf" onclick="viewGeneratedPdf('{{ $status->application_id }}', 25)">View Hostel PDF</button>
                    <button type="button" class="btn btn-library-pdf" onclick="viewGeneratedPdf('{{ $status->application_id }}', 12)">View Library PDF</button>
                    @endif
                </div>

                <!-- Approval and Decline Buttons on the Right -->
                <div class="approval-buttons">
                    @if(auth()->user()->dep_id != 14)
                        @if (!$isEnlistment || ($isEnlistment && $status->allOthersApproved))
                        <form action="{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => $status->id]) }}" 
                            method="POST" onsubmit="return setPersonNameBeforeSubmit('{{ $status->id }}')">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="APPROVED">
                            <button type="submit" class="btn btn-approve">Approve</button>
                        </form>
                        @else
                        <div class="approval-container">
                            <button class="btn btn-approve" disabled>Approve</button>
                            <small class="text-danger">Departments are not completed</small>
                        </div>
                        @endif
                        <button type="button" class="btn btn-decline" onclick="declineApplication('{{ $status->id }}')">Decline</button>
                    @endif
                </div>
            
                <!-- Show More Button -->
                    @php
                    $hideShowMoreButton = in_array(auth()->user()->dep_id, [3,31,32,33,34,35,36,37,38,39,40, 4, 5, 6, 7, 9,
                    10, 12, 13]);
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
</div>
</div>

    
    <script>
       document.addEventListener('DOMContentLoaded', () => {
    const allRequestsCheckbox = document.getElementById('allRequests');
    const approvedRequestsCheckbox = document.getElementById('approvedRequests');
    const rejectedRequestsCheckbox = document.getElementById('rejectedRequests');
    const applicationItems = document.querySelectorAll('.application-item');

    // Function to filter applications
    function filterApplications() {
        const showAll = allRequestsCheckbox.checked;
        const showApproved = approvedRequestsCheckbox.checked;
        const showRejected = rejectedRequestsCheckbox.checked;

        applicationItems.forEach(item => {
            const statusBadge = item.querySelector('.status-badge');
            const status = statusBadge ? statusBadge.textContent.trim().toUpperCase() : null;

            // Determine visibility
            if (showAll) {
                item.style.display = 'block';
            } else if (showApproved && status === 'APPROVED') {
                item.style.display = 'block';
            } else if (showRejected && status === 'REJECTED') {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Event listeners for checkboxes
    allRequestsCheckbox.addEventListener('change', () => {
        if (allRequestsCheckbox.checked) {
            approvedRequestsCheckbox.checked = false;
            rejectedRequestsCheckbox.checked = false;
        }
        filterApplications();
    });

    approvedRequestsCheckbox.addEventListener('change', () => {
        if (approvedRequestsCheckbox.checked) {
            allRequestsCheckbox.checked = false;
        }
        filterApplications();
    });

    rejectedRequestsCheckbox.addEventListener('change', () => {
        if (rejectedRequestsCheckbox.checked) {
            allRequestsCheckbox.checked = false;
        }
        filterApplications();
    });

    // Initial filter to show all applications
    filterApplications();
});

    ////////////////////////////////////////////    
    function declineApplication(statusId) {
        console.log("Decline button clicked for status ID:", statusId);

        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert("CSRF token not found. Please check your layout file.");
            return;
        }

        // Prompt the user for the reason
        var reason = prompt("Please enter the reason for declining:");
        if (reason == null || reason.trim() === "") {
            console.log("Decline action cancelled or no reason provided.");
            return; // Exit if no reason provided
        }

        console.log("Reason provided:", reason);

        // Prepare the data to send
        var formData = new FormData();
        formData.append('_token', csrfToken.getAttribute('content'));
        formData.append('_method', 'PUT');
        formData.append('status', 'REJECTED');
        formData.append('reason', reason);

        // Make the fetch request
        fetch(`{{ route('Clearance.update', ['departmentId' => auth()->user()->dep_id, 'statusId' => ':statusId']) }}`
                .replace(':statusId', statusId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.message ||
                            `Network response was not ok (${response.status})`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Reload the page
                } else {
                    alert('Error: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            });
    }

    function generatePdf(statusId) {
        console.log("Generate PDF button clicked for status ID:", statusId);

        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert("CSRF token not found. Please check your layout file.");
            return;
        }

        // Prompt the user for additional information or reasons
        var pdfReason = prompt("Please enter the reason or information for the PDF:");
        if (pdfReason == null || pdfReason.trim() === "") {
            console.log("PDF generation cancelled or no reason provided.");
            return; // Exit if no reason provided
        }

        console.log("Reason provided for PDF:", pdfReason);

        // Prepare the data to send
        var formData = new FormData();
        formData.append('_token', csrfToken.getAttribute('content'));
        formData.append('pdf_reason', pdfReason);

        // Make the fetch request
        fetch(`{{ route('Clearance.generatePdf', ['departmentId' => auth()->user()->dep_id, 'statusId' => ':statusId']) }}`
                .replace(':statusId', statusId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.message ||
                            `Network response was not ok (${response.status})`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    // Optionally, provide a link to view the PDF
                    // location.reload(); // Reload the page if needed
                } else {
                    alert('Error: ' + (data.message || 'Unknown error occurred'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            });
    }

    function viewGeneratedPdf(applicationId, departmentId) {
        console.log("View PDF button clicked for application ID:", applicationId, "and department ID:", departmentId);

        // Construct the correct file URL directly
        const fileUrl = `/storage/pdfs/application_${applicationId}_${departmentId}.pdf`;

        // Open the file in a new tab
        window.open(fileUrl, '_blank');
    }


    
    </script>

</div>
@endsection