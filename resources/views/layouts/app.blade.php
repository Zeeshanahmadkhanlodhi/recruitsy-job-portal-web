<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="jobs-api-base" content="{{ env('JOBS_API_BASE') }}">

    <title>@yield('title', 'RecruitSy - Job Portal')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="nav-brand">
                <a href="{{ route('home') }}" class="logo">
                    RecruitSy
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('jobs') }}">Jobs</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
            </ul>
            <div class="nav-actions">
                @guest
                    <a href="{{ route('signin') }}" class="btn btn-sm">Sign In</a>
                    <a href="{{ route('signup') }}" class="btn btn-primary btn-sm">Sign Up</a>
                @endguest
                @auth
                    <div class="nav-user">
                        <a href="{{ route('profile') }}" class="nav-avatar" title="Profile">
                            @if (auth()->user()->avatar_path)
                                <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="Avatar">
                            @else
                                <span class="avatar-initials">{{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'U', 0, 1)) }}</span>
                            @endif
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm">Logout</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>RecruitSy</h3>
                    <p>Connecting talented professionals with amazing opportunities. Find your dream job and advance your career.</p>
                </div>
                <div class="footer-section">
                    <h3>For Job Seekers</h3>
                    <ul>
                        <li><a href="{{ route('jobs') }}">Browse Jobs</a></li>
                        <li><a href="{{ route('signup') }}">Create Profile</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 RecruitSy. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 