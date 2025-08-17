@extends('layouts.app')

@section('title', 'Sign Up - RecruitSy')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Create Your Account</h1>
            <p class="auth-subtitle">Join thousands of professionals finding their dream jobs</p>
        </div>

        <form class="auth-form" action="{{ route('signup.post') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="form-input" placeholder="Enter your first name">
                </div>
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="form-input" placeholder="Enter your last name">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" required class="form-input" placeholder="Create a password">
                <div class="password-requirements">
                    <div class="requirement">
                        <span class="requirement-icon">✓</span>
                        <span class="requirement-text">At least 8 characters</span>
                    </div>
                    <div class="requirement">
                        <span class="requirement-icon">✓</span>
                        <span class="requirement-text">One uppercase letter</span>
                    </div>
                    <div class="requirement">
                        <span class="requirement-icon">✓</span>
                        <span class="requirement-text">One number</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="Confirm your password">
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" class="checkbox-input" required>
                    <span class="checkbox-text">I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a></span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" class="checkbox-input">
                    <span class="checkbox-text">Send me job alerts and career tips</span>
                </label>
            </div>

                            <button type="submit" class="btn btn-primary btn-lg auth-btn">Create Account</button>
            @if ($errors->any())
                <div style="margin-top: 1rem; color: #b91c1c; font-size: 0.875rem;">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>

        <div class="divider">
            <span class="divider-text">or sign up with</span>
        </div>

        <div class="social-auth">
            <a class="social-btn google btn btn-outline" href="{{ route('google.redirect') }}">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Continue with Google</span>
            </a>
            
            <a class="social-btn linkedin btn btn-outline" href="{{ route('linkedin.redirect') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.37c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                <span>Continue with LinkedIn</span>
            </a>
        </div>

        <div class="auth-footer">
            <p class="auth-footer-text">
                Already have an account? 
                <a href="{{ route('signin') }}" class="auth-link">Sign in</a>
            </p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 1rem;
}

.auth-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    padding: 3rem;
    width: 100%;
    max-width: 500px;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 2rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.auth-form {
    margin-bottom: 2rem;
}

.auth-btn {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    margin-top: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin: 1.5rem 0;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-input {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    border: 1px solid #d1d5db;
    margin-top: 0.125rem;
}

.checkbox-text {
    color: #374151;
    font-size: 0.875rem;
    line-height: 1.4;
}

.terms-link {
    color: #2563eb;
    text-decoration: none;
}

.terms-link:hover {
    text-decoration: underline;
}

.password-requirements {
    margin-top: 0.5rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 0.375rem;
}

.requirement {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
}

.requirement:last-child {
    margin-bottom: 0;
}

.requirement-icon {
    color: #10b981;
    font-weight: bold;
}

.divider {
    position: relative;
    text-align: center;
    margin: 2rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e5e7eb;
}

.divider-text {
    background: white;
    padding: 0 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.social-auth {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
    color: #374151;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.social-btn:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.social-btn.google {
    color: #374151;
}

.social-btn.linkedin {
    color: #0077b5;
}

.auth-footer {
    text-align: center;
}

.auth-footer-text {
    color: #6b7280;
    font-size: 0.875rem;
}

.auth-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.auth-link:hover {
    text-decoration: underline;
}

@media (max-width: 640px) {
    .auth-card {
        padding: 2rem 1.5rem;
    }
    
    .auth-title {
        font-size: 1.75rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection 