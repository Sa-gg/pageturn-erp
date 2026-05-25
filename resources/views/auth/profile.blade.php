@extends('layouts.app')

@section('title', 'My Profile')

@section('styles')
<style>
    .profile-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 2.5rem;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-forest), var(--color-gold));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-white);
        box-shadow: 0 4px 20px rgba(45, 95, 43, 0.3);
        flex-shrink: 0;
    }

    .profile-header-info h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--color-brown-dark);
        margin-bottom: 4px;
    }

    .profile-header-info .profile-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .profile-role-admin {
        background: linear-gradient(135deg, #FFE8E8, #FFD4D4);
        color: #C53030;
    }

    .profile-role-staff {
        background: linear-gradient(135deg, #E8F4FD, #D1E8F8);
        color: #2B6CB0;
    }

    .profile-role-customer {
        background: linear-gradient(135deg, #E8F8E8, #D4EDD4);
        color: var(--color-forest);
    }

    .profile-card {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--color-forest), var(--color-gold), var(--color-brown));
    }

    .profile-card-title {
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--color-brown-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-card-title i {
        color: var(--color-forest);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.83rem;
        font-weight: 600;
        color: var(--color-gray-700);
        margin-bottom: 6px;
    }

    .form-input-wrapper {
        position: relative;
    }

    .form-input-wrapper .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-gray-300);
        font-size: 0.95rem;
        pointer-events: none;
    }

    .form-input {
        width: 100%;
        padding: 12px 14px 12px 44px;
        border: 2px solid var(--color-gray-200);
        border-radius: var(--radius-md);
        font-family: var(--font-primary);
        font-size: 0.93rem;
        color: var(--color-gray-700);
        background: var(--color-white);
        transition: all var(--transition-base);
        outline: none;
    }

    .form-input:hover {
        border-color: var(--color-gray-300);
    }

    .form-input:focus {
        border-color: var(--color-forest);
        box-shadow: 0 0 0 4px rgba(45, 95, 43, 0.1);
    }

    .form-input:disabled {
        background: var(--color-gray-100);
        color: var(--color-gray-500);
        cursor: not-allowed;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-textarea {
        padding: 12px 14px;
        min-height: 80px;
        resize: vertical;
    }

    .profile-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--color-gray-200);
    }

    .auth-submit-btn {
        padding: 12px 28px;
        background: linear-gradient(135deg, var(--color-forest), var(--color-forest-light));
        color: var(--color-white);
        border: none;
        border-radius: var(--radius-md);
        font-family: var(--font-primary);
        font-size: 0.92rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-base);
        box-shadow: 0 4px 16px rgba(45, 95, 43, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .auth-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(45, 95, 43, 0.4);
    }

    .profile-meta {
        display: flex;
        gap: 24px;
        margin-top: 1.5rem;
        padding: 16px 20px;
        background: var(--color-cream);
        border-radius: var(--radius-md);
    }

    .profile-meta-item {
        font-size: 0.82rem;
        color: var(--color-gray-500);
    }

    .profile-meta-item strong {
        color: var(--color-gray-700);
    }

    .invalid-feedback {
        color: var(--color-danger);
        font-size: 0.78rem;
        margin-top: 5px;
    }

    /* Success Alert */
    .profile-success-alert {
        background: #D4EDDA;
        border: 1px solid #C3E6CB;
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #155724;
        font-size: 0.88rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .profile-page {
            padding: 2rem 1rem;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .profile-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-page">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
        </div>
        <div class="profile-header-info">
            <h1>{{ $user['name'] ?? 'User' }}</h1>
            <span class="profile-role profile-role-{{ $user['role'] ?? 'customer' }}">
                <i class="fas fa-shield-alt"></i>
                {{ ucfirst($user['role'] ?? 'customer') }}
            </span>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="profile-card">
        <div class="profile-card-title">
            <i class="fas fa-user-edit"></i>
            Account Information
        </div>

        @if(session('status'))
            <div class="profile-success-alert">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->has('profile'))
            <div class="auth-error-alert" style="background: #FFF5F5; border: 1px solid #FECDD3; border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; color: var(--color-danger); font-size: 0.88rem;">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $errors->first('profile') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="form-input-wrapper">
                    <input id="email" type="email" class="form-input" value="{{ $user['email'] ?? '' }}" disabled>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                <small style="color: var(--color-gray-500); font-size: 0.75rem;">Email cannot be changed.</small>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <div class="form-input-wrapper">
                    <input
                        id="name"
                        type="text"
                        class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        name="name"
                        value="{{ old('name', $user['name'] ?? '') }}"
                        required
                        placeholder="Your full name"
                    >
                    <i class="fas fa-user input-icon"></i>
                </div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <div class="form-input-wrapper">
                        <input
                            id="phone"
                            type="text"
                            class="form-input"
                            name="phone"
                            value="{{ old('phone', $user['phone'] ?? '') }}"
                            placeholder="09xxxxxxxxx"
                        >
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <div class="form-input-wrapper">
                        <input
                            id="address"
                            type="text"
                            class="form-input"
                            name="address"
                            value="{{ old('address', $user['address'] ?? '') }}"
                            placeholder="City, Country"
                        >
                        <i class="fas fa-map-marker-alt input-icon"></i>
                    </div>
                </div>
            </div>

            <div class="profile-meta">
                <div class="profile-meta-item">
                    <strong>Member since:</strong> {{ \Carbon\Carbon::parse($user['created_at'] ?? now())->format('M d, Y') }}
                </div>
                <div class="profile-meta-item">
                    <strong>Account status:</strong>
                    <span style="color: {{ ($user['is_active'] ?? true) ? 'var(--color-forest)' : 'var(--color-danger)' }};">
                        {{ ($user['is_active'] ?? true) ? '● Active' : '● Inactive' }}
                    </span>
                </div>
            </div>

            <div class="profile-actions">
                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
