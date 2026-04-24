@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=667eea&color=fff" class="rounded-circle mb-3" width="80">
                    <h5>{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                    <hr>
                    <a href="{{ route('customer.profile') }}" class="btn btn-primary btn-sm w-100 mb-2">
                        <i class="fas fa-user me-2"></i> My Profile
                    </a>
                    <a href="{{ route('customer.reservations') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-calendar-alt me-2"></i> My Reservations
                    </a>
                    <a href="{{ route('customer.tickets') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-ticket-alt me-2"></i> My Tickets
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Profile Saya</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Ganti Password</h5>
                    <form method="POST" action="{{ route('customer.password.change') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection