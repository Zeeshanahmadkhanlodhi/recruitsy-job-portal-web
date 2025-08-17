@extends('layouts.app')

@section('title', 'About Us - RecruitSy')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>About RecruitSy</h1>
        <p>Revolutionizing the way people find jobs and companies find talent</p>
    </div>
</div>

<!-- Mission Section -->
<div class="bg-white py-16">
    <div class="container">
        <div class="grid grid-2">
            <div>
                <h2 class="card-title">Our Mission</h2>
                <p class="text-gray-600 mb-6">
                    At RecruitSy, we believe that everyone deserves to find meaningful work that aligns with their passions and skills. Our mission is to bridge the gap between talented professionals and innovative companies, creating opportunities for growth and success.
                </p>
                <p class="text-gray-600 mb-6">
                    We're committed to making the job search process more human, more efficient, and more successful for both job seekers and employers. Through our innovative platform, we're building a community where connections lead to careers.
                </p>
                <div class="trust-badge">
                    <div class="trust-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="trust-text">Trusted by 50,000+ professionals</span>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">2018</div>
                        <div class="stat-label">Founded</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Team Members</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">25+</div>
                        <div class="stat-label">Countries</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="bg-gray-50 py-16">
    <div class="container">
        <div class="text-center mb-8">
            <h2 class="card-title">Our Values</h2>
            <p class="text-gray-600">The principles that guide everything we do</p>
        </div>
        
        <div class="grid grid-3">
            <div class="card text-center">
                <div class="value-icon passion">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="card-title">Passion</h3>
                <p class="text-gray-600">We're passionate about connecting people with opportunities that ignite their potential and drive their success.</p>
            </div>
            
            <div class="card text-center">
                <div class="value-icon trust">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="card-title">Trust</h3>
                <p class="text-gray-600">We build trust through transparency, reliability, and by always putting our users' needs first.</p>
            </div>
            
            <div class="card text-center">
                <div class="value-icon innovation">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="card-title">Innovation</h3>
                <p class="text-gray-600">We continuously innovate to provide cutting-edge solutions that make job hunting and hiring more effective.</p>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="bg-white py-16">
    <div class="container">
        <div class="text-center mb-8">
            <h2 class="card-title">Meet Our Team</h2>
            <p class="text-gray-600">The passionate people behind RecruitSy</p>
        </div>
        
        <div class="grid grid-3">
            <div class="team-member">
                <div class="member-avatar">
                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z"/>
                    </svg>
                </div>
                <h3 class="member-name">Sarah Johnson</h3>
                <p class="member-title">CEO & Founder</p>
                <p class="member-bio">Former HR executive with 15+ years of experience in talent acquisition and workforce development.</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z"/>
                    </svg>
                </div>
                <h3 class="member-name">Michael Chen</h3>
                <p class="member-title">CTO</p>
                <p class="member-bio">Tech leader with expertise in AI and machine learning, driving our platform's intelligent matching algorithms.</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8c0 2.208-1.79 4-3.998 4-2.208 0-3.998-1.792-3.998-4s1.79-4 3.998-4c2.208 0 3.998 1.792 3.998 4z"/>
                    </svg>
                </div>
                <h3 class="member-name">Emily Rodriguez</h3>
                <p class="member-title">Head of Product</p>
                <p class="member-bio">Product strategist focused on creating seamless user experiences that delight both job seekers and employers.</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="bg-gray-50 py-16">
    <div class="container text-center">
        <h2 class="card-title">Get in Touch</h2>
        <p class="text-gray-600 mb-8">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        <div class="grid grid-3">
            <div class="contact-item">
                <div class="contact-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="contact-title">Email</h3>
                <p class="contact-info">hello@recruitsy.com</p>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="contact-title">Phone</h3>
                <p class="contact-info">+1 (555) 123-4567</p>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="contact-title">Office</h3>
                <p class="contact-info">San Francisco, CA</p>
            </div>
        </div>
    </div>
</div>

<style>
.trust-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.trust-icon {
    background: #dbeafe;
    padding: 0.75rem;
    border-radius: 50%;
    color: #2563eb;
}

.trust-text {
    color: #374151;
    font-weight: 500;
}

.stats-card {
    background: #f3f4f6;
    border-radius: 0.5rem;
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.stat-item {
    text-align: center;
}

.value-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.value-icon.passion {
    background: #fef2f2;
    color: #dc2626;
}

.value-icon.trust {
    background: #f0fdf4;
    color: #16a34a;
}

.value-icon.innovation {
    background: #faf5ff;
    color: #9333ea;
}

.team-member {
    text-align: center;
}

.member-avatar {
    width: 128px;
    height: 128px;
    background: #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: #9ca3af;
}

.member-name {
    font-size: 1.25rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.member-title {
    color: #2563eb;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.member-bio {
    color: #6b7280;
    font-size: 0.875rem;
}

.contact-item {
    text-align: center;
}

.contact-icon {
    width: 48px;
    height: 48px;
    background: #dbeafe;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: #2563eb;
}

.contact-title {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.contact-info {
    color: #6b7280;
}
</style>
@endsection 