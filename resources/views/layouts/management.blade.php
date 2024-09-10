@extends('layouts.app')

@section('title', 'Management Dashboard')

@section('content')
    <div class="sidebar">
        <!-- Management-specific navigation -->
    </div>
    <div class="main-content">
        @yield('dashboard-content')
    </div>
@endsection
