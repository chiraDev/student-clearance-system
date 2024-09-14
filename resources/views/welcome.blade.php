<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('welcomelogin.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    @include('components.nav')
    <div class="center-container">
    <div class="form-wrapper">
        <h2 class="form-title">Log in to your account</h2>
        <h3 class="form-sub-title">Welcome to CMS KDU</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <!-- Email Address -->
                <input id="email" type="email" name="email" required autofocus placeholder="Email Address">
            </div>

            <div class="form-group">
                <!-- Password -->
                <input id="password" type="password" name="password" required placeholder="Password">
            </div>

            <div class="remember-me">
                <!-- Remember Me -->
                {{-- <div>
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div> --}}

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot your password?</a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">Login</button>
        </form>

        {{-- <div class="register-prompt">
            @if (Route::has('register'))
                <p>
                    Don't have an account?
                    <a href="{{ route('register') }}">Register</a>
                </p>
            @endif
        </div> --}}
    </div>
    <img src="/images/lg_art.png" alt="Descriptive Alt Text" class="side-image">

    </div>
</body>
</html>
