<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    /* Global CSS Variables */
    :root {
      --primary-color: #4CAF50;
      --secondary-color: #ffffff;
      --text-color: #333;
      --text-light-color: #666;
      --button-bg: #d0e7f9;
      --button-hover-bg: #b3d4f0;
      --button-active-bg: #4CAF50;
      --shadow-color: rgba(0, 0, 0, 0.15);
      --sidebar-width: 250px;
      --content-padding: 20px;
      --font-family: 'Arial', sans-serif;
      --font-size-base: 16px;
      --font-size-lg: 18px;
      --font-size-sm: 14px;
    }

    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      font-family: var(--font-family);
      font-size: var(--font-size-base);
      background-color: #f8f8f8;
      color: var(--text-color);
      line-height: 1.6;
      display: flex;
    }

    body {
      display: flex;
      flex-direction: row;
      overflow: hidden;
    }

    /* Sidebar Styles */
    #nav-bar {
      width: var(--sidebar-width);
      background-color: var(--secondary-color);
      box-shadow: 0 0 15px var(--shadow-color);
      display: flex;
      flex-direction: column;
      padding: var(--content-padding);
      height: 100vh;
      align-items: flex-start;
      position: relative;
      transition: transform 0.3s ease;
      z-index: 1000;
    }

    #nav-title {
      font-size: var(--font-size-lg);
      font-weight: bold;
      color: var(--text-color);
      margin-bottom: 30px;
      cursor: pointer;
      display: flex;
      align-items: center;
    }

    #nav-title i {
      margin-right: 10px;
      transition: transform 0.3s ease;
    }

    #nav-title:hover i {
      transform: rotate(180deg);
    }

    #nav-content {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .nav-button {
      background-color: var(--button-bg);
      color: var(--text-color);
      padding: 12px;
      border-radius: 25px;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
      font-size: var(--font-size-base);
      text-decoration: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .nav-button i {
      margin-right: 8px;
      transition: transform 0.3s ease;
    }

    .nav-button:hover {
      background-color: var(--button-hover-bg);
      transform: translateX(5px);
    }

    .nav-button:hover i {
      transform: translateX(5px);
    }

    .nav-button.active {
      background-color: var(--button-active-bg);
      color: #fff;
      box-shadow: 0 4px 12px var(--shadow-color);
    }

    /* Main Content Styles */
    #main-content {
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      padding: var(--content-padding);
      background-color: var(--secondary-color);
      box-shadow: 0 0 15px var(--shadow-color);
      overflow-y: auto;
      width: calc(100vw - var(--sidebar-width)); /* Adjust width based on sidebar */
      height: 100vh;
      min-height: 100vh;
      transition: width 0.3s ease;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
      #nav-bar {
        width: 200px;
      }

      .nav-button {
        font-size: var(--font-size-sm);
        padding: 10px;
      }

      #main-content {
        padding: 15px;
      }
    }

    @media (max-width: 576px) {
      #nav-bar {
        transform: translateX(-100%);
        position: absolute;
        left: 0;
      }

      body.sidebar-active #nav-bar {
        transform: translateX(0);
      }

      #main-content {
        width: 100vw;
        padding: 10px;
      }
    }

    /* Animated menu toggle button */
    #menu-toggle {
      display: none;
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 28px;
      cursor: pointer;
      z-index: 1100;
    }

    @media (max-width: 576px) {
      #menu-toggle {
        display: block;
      }
    }
  </style>

    <!-- Other meta tags and styles -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
  <!-- Sidebar Toggle Icon for small screens -->
  <div id="menu-toggle">
    <i class="fas fa-bars"></i>
  </div>

  <div id="nav-bar">
    <a id="nav-title" href="#">
      <i class="fas fa-cog"></i> Dashboard
    </a>
    
    <nav id="nav-content">
      @auth
      @php
        $dep_id = Auth::user()->dep_id;
      @endphp
  
      @if($dep_id == 1)
        <!-- Common Buttons -->
        <a class="nav-button" href="{{ route('student.dashboard') }}">
          <i class="fas fa-home"></i> Dashboard
        </a>
        
      @elseif($dep_id == 3)
        <a class="nav-button" href="{{ route('vc.vc') }}">
          <i class="fas fa-cogs"></i> Home
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 15)
        <a class="nav-button" href="{{ route('helpdesk.helpdesk') }}">
          <i class="fas fa-headset"></i> Helpdesk
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 16)
        <a class="nav-button" href="{{ route('enlistment.enlistment') }}">
          <i class="fas fa-user-plus"></i> Enlistment
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 9)
        <a class="nav-button" href="{{ route('sods.sods') }}">
          <i class="fas fa-users"></i> SODS
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 4)
        <a class="nav-button" href="{{ route('ocus.ocus') }}">
          <i class="fas fa-cogs"></i> OCUS
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 5)
        <a class="nav-button" href="{{ route('logOfficer.log') }}">
          <i class="fas fa-file"></i> Log Officer
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 14)
        <a class="nav-button" href="{{ route('accsec.accsec') }}">
          <i class="fas fa-money-check"></i> ACCSEC
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 13)
        <a class="nav-button" href="{{ route('library.library') }}">
          <i class="fas fa-book"></i> Library
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 6)
        <a class="nav-button" href="{{ route('arfoc.arfoc') }}">
          <i class="fas fa-globe"></i> ARFOC
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 7)
        <a class="nav-button" href="{{ route('cadetmess.cadetmess') }}">
          <i class="fas fa-globe"></i> Cadet Mess
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 8)
        <a class="nav-button" href="{{ route('publication.publication') }}">
          <i class="fas fa-globe"></i> Publication
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @elseif($dep_id == 10)
        <a class="nav-button" href="{{ route('tso.tso') }}">
          <i class="fas fa-globe"></i> TSO
        </a>
        <a class="nav-button" href="{{ route('Clearance.list', ['departmentId' => auth()->user()->dep_id]) }}">
          <i class="fas fa-cogs"></i> Clearance
        </a>
  
      @endif
  
      <!-- Add Staff Button for All Departments -->
      <a class="nav-button" href="{{ route('ranks.create') }}">
        <i class="fas fa-user-plus"></i> Add Staff
      </a>
  
      <a class="nav-button" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </a>
  
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      @endauth
  </nav>
  
  </div>

  <main id="main-content">
    @yield('content')
  </main>

  <script>
    // Activate button on click
    document.querySelectorAll('.nav-button').forEach(button => {
      button.addEventListener('click', function () {
        document.querySelectorAll('.nav-button').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Responsive sidebar for smaller screens
    const body = document.body;
    const menuToggle = document.querySelector('#menu-toggle');
    
    menuToggle.addEventListener('click', () => {
      body.classList.toggle('sidebar-active');
    });
  </script>
</body>
</html>
