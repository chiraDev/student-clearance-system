<!-- resources/views/ranks/create.blade.php -->

@extends('layouts.app') <!-- Assuming you have a layout file -->

@section('title', 'Add Person')

@section('content')
<div class="container">
    <h1>Add Person</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('ranks.store') }}" method="POST">
        @csrf

        <!-- Hidden field for department_id -->
        <input type="hidden" name="department_id" value="{{ $departmentId }}" required>

        <div class="form-group">
            <label for="rank_name">Rank Name</label>
            <input type="text" name="rank_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="person_name">Person's Name</label>
            <input type="text" name="person_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Person</button>
    </form>
</div>
@endsection
