@php
use Illuminate\Support\Facades\Route;
@endphp

<head>
    <link rel="stylesheet" href="{{ asset('css/student-dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome -->
</head>

@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')

{{-- <h1 class="dashboard-title">Student Dashboard</h1> --}}
<div>
    <!-- The main container -->

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    {{-- <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">
        Logout
    </button>
    </form> --}}
    <div class="card">
        <div class="card-header">Clearance Application</div>
        <div class="card-body">
            <form method="POST" action="{{ route('student.submitClearanceForm') }}"
                onsubmit="disableSubmitButton(this)">
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
                    <button type="submit" class="btn btn-primary" id="submitButton"
                        {{ $application ? 'disabled' : '' }}>
                        {{ $application ? 'Application Submitted' : 'Submit Clearance Form' }}
                    </button>

                    @if ($allApproved)
                    <!-- Condition to check if all departments have approved -->
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
                        <th>Rank</th> <!-- Existing Rank Column -->
                        <th>PDF</th> <!-- New PDF Column -->
                        <th>Upload Receipt</th> <!-- New Upload Receipt Column -->
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

                        @if (in_array(strtolower($status->department->dep_name), ['library', 'hostal']))
                        {{-- PDF Column --}}
                        <td>
                            @if($status->pdf_path)
                            @php
                            // Determine the route based on department
                            $departmentName = strtolower($status->department->dep_name);
                            $routeName = $departmentName === 'hostel' ? 'clearance.pdf.hostel' :
                            'clearance.pdf.library';
                            $applicationId = $status->application_id ?? $status->id;
                            @endphp
                            <button class="btn btn-link view-pdf"
                                data-url="{{ route($routeName, ['applicationId' => $applicationId]) }}">
                                <i class="fas fa-file-pdf"></i> View PDF
                            </button>
                            @else
                            N/A
                            @endif
                        </td>
                        <div id="pdfModal"
                            style="display: none; position: fixed; top: 60; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8);">
                            <div
                                style="position: relative; width: 80%; height: 80%; margin: 5% auto; background: #fff; border-radius: 5px; overflow: hidden;">
                                <button id="closeModal"
                                    style="position: absolute; top: 10px; right: 10px; background: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">X</button>
                                <iframe id="pdfIframe" style="width: 100%; height: 100%;" frameborder="0"></iframe>
                            </div>
                        </div>

                        <td>
                            @if($status->status === 'PENDING' )
                            <form method="POST" action="{{ route('student.uploadReceipt') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="department_id" value="{{ $status->department->id }}">
                                <div class="input-group">
                                    <input type="file" name="receipt" accept="application/pdf,image/*" required>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-upload"></i> Upload
                                    </button>
                                </div>
                            </form>
                            @elseif($status->receipt_path)
                            <a href="{{ Storage::url($status->receipt_path) }}" target="_blank">
                                <i class="fas fa-file-upload"></i> View Receipt
                            </a>
                            @else
                            Pending Upload
                            @endif
                        </td>

                        @else
                        <td>N/A</td>
                        <td>N/A</td>
                        @endif

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <script>
    function disableSubmitButton(form) {
        form.querySelector('#submitButton').disabled = true;
        form.querySelector('#submitButton').textContent = 'Submitting...';
    }
    document.addEventListener('DOMContentLoaded', function() {
        const viewPdfButtons = document.querySelectorAll('.view-pdf');
        const modal = document.getElementById('pdfModal');
        const modalIframe = document.getElementById('pdfIframe');

        viewPdfButtons.forEach(button => {
            button.addEventListener('click', function() {
                const url = this.dataset.url;

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Identifies this as an AJAX request
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.pdf_url) {
                            // Display the PDF in the modal
                            modalIframe.src = data.pdf_url;
                            modal.style.display = 'block';
                        } else {
                            alert('Failed to retrieve the PDF.');
                        }
                    })
                    .catch(error => {
                        console.log('Error fetching PDF:', error);
                        alert('An error occurred while fetching the PDF.');
                    });
            });
        });

        // Close the modal
        document.getElementById('closeModal').addEventListener('click', function() {
            modal.style.display = 'none';
            modalIframe.src = ''; // Clear the iframe
        });
    });
    </script>

    @endsection