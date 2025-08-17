@extends('layouts.app')

@section('title', 'Home - RecruitSy')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>Find Your Dream Job</h1>
        <p>Connect with top employers and discover opportunities that match your skills and aspirations</p>
        <div class="hero-buttons">
                            <a href="{{ route('jobs') }}" class="btn btn-primary btn-lg">Browse Jobs</a>
            <a href="{{ route('signup') }}" class="btn btn-outline">Create Profile</a>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="bg-white py-16">
    <div class="container">
        <div class="card">
            <h2 class="card-title text-center">Search for Jobs</h2>
            <form class="grid grid-2">
                <div class="form-group">
                    <label for="keyword" class="form-label">Job Title or Keyword</label>
                    <input type="text" id="keyword" name="keyword" placeholder="e.g. Software Engineer" class="form-input">
                </div>
                <div class="form-group">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" id="location" name="location" placeholder="e.g. New York, NY" class="form-input">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Search Jobs</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-gray-50 py-16">
    <div class="container">
        <div class="text-center mb-8">
            <h2 class="card-title">Why Choose RecruitSy?</h2>
            <p class="text-gray-600">We make job hunting and hiring simple, efficient, and effective</p>
        </div>
        
        <div class="grid grid-3">
            <div class="card text-center">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="card-title">Smart Job Matching</h3>
                <p class="text-gray-600">Our AI-powered algorithm matches you with the most relevant job opportunities based on your skills and preferences.</p>
            </div>
            
            <div class="card text-center">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="card-title">Quick Application</h3>
                <p class="text-gray-600">Apply to multiple jobs with just a few clicks. Save time with our streamlined application process.</p>
            </div>
            
            <div class="card text-center">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="card-title">Direct Communication</h3>
                <p class="text-gray-600">Connect directly with employers and recruiters. No middlemen, faster responses.</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-blue-600 text-white py-16">
    <div class="container">
        <div class="grid grid-3 text-center">
            <div>
                <div class="stat-number">10,000+</div>
                <div class="stat-label">Active Jobs</div>
            </div>
            <div>
                <div class="stat-number">5,000+</div>
                <div class="stat-label">Companies</div>
            </div>
            <div>
                <div class="stat-number">50,000+</div>
                <div class="stat-label">Job Seekers</div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-white py-16">
    <div class="container text-center">
        <h2 class="card-title">Ready to Start Your Journey?</h2>
        <p class="text-gray-600 mb-8">Join thousands of professionals who have found their dream jobs through RecruitSy</p>
        <div class="hero-buttons">
                            <a href="{{ route('signup') }}" class="btn btn-primary btn-lg">Get Started Today</a>
            <a href="{{ route('about') }}" class="btn btn-outline">Learn More</a>
        </div>
    </div>
</div>

<style>
.feature-icon {
    width: 64px;
    height: 64px;
    background: #dbeafe;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: #2563eb;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-label {
    opacity: 0.9;
    font-size: 1.125rem;
}
</style>
@endsection 