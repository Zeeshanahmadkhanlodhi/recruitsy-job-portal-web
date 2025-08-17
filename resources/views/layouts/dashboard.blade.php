<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard - RecruitSy')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="logo">
                    RecruitSy
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard-jobs') }}" class="nav-link {{ request()->routeIs('dashboard-jobs') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            <span>Find Jobs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('applications') }}" class="nav-link {{ request()->routeIs('applications') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Applications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('messages') }}" class="nav-link {{ request()->routeIs('messages') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m1 8l-4-4H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v4a4 4 0 01-4 4h-1l-4 4z"/>
                            </svg>
                            <span>Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('saved-jobs') }}" class="nav-link {{ request()->routeIs('saved-jobs') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <span>Saved Jobs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('job-alerts') }}" class="nav-link {{ request()->routeIs('job-alerts') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h6v-2H4v2zM4 11h6V9H4v2zM4 7h6V5H4v2z"></path>
                            </svg>
                            <span>Job Alerts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Top Header -->
            <header class="dashboard-header">
                <div class="header-left">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <div class="user-info">
                            <div class="user-avatar">
                                @if (auth()->user()->avatar_path)
                                    <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;" />
                                @else
                                    <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ auth()->user()->first_name ? (auth()->user()->first_name.' '.auth()->user()->last_name) : (auth()->user()->name ?? auth()->user()->email) }}</span>
                                <span class="user-role">Logged in</span>
                            </div>
                        </div>
                        <div class="user-actions">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="dashboard-content">
                @yield('content')
            </div>
        </main>
    </div>

    <style>
    body {
        margin: 0;
        padding: 0;
        background: #f9fafb;
        font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .dashboard-layout {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styles */
    .dashboard-sidebar {
        width: 280px;
        background: white;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2563eb;
        text-decoration: none;
    }

    .sidebar-nav {
        flex: 1;
        padding: 1rem 0;
    }

    .nav-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin: 0.25rem 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.3s;
        border-radius: 0.375rem;
        margin: 0 0.5rem;
    }

    .nav-link:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .nav-link.active {
        background: #dbeafe;
        color: #2563eb;
        font-weight: 500;
    }

    .nav-link svg {
        flex-shrink: 0;
    }

    /* Main Content Styles */
    .dashboard-main {
        flex: 1;
        margin-left: 280px;
        display: flex;
        flex-direction: column;
    }

    .dashboard-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .user-menu {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 500;
        color: #1f2937;
        font-size: 0.875rem;
    }

    .user-role {
        color: #6b7280;
        font-size: 0.75rem;
    }

    .user-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .dashboard-content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .dashboard-sidebar {
            width: 240px;
        }
        
        .dashboard-main {
            margin-left: 240px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 20;
        }
        
        .dashboard-sidebar.open {
            transform: translateX(0);
        }
        
        .dashboard-main {
            margin-left: 0;
        }
        
        .dashboard-header {
            padding: 1rem;
        }
        
        .dashboard-content {
            padding: 1rem;
        }
        
        .user-details {
            display: none;
        }
    }
    </style>
</body>
</html> 