@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #0d6efd, #6610f2) !important;
        min-height: 100vh;
    }

    .login-card {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.15);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 30px;
        animation: fadeIn 0.7s ease;
    }

    .login-card input {
        background: rgba(255, 255, 255, 0.85);
        border: none;
        padding: 12px;
        border-radius: 10px;
    }

    .login-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 15px auto;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
    }

    .login-icon i {
        font-size: 38px;
        color: #0d6efd;
    }

    button {
        border-radius: 12px !important;
        padding: 12px;
        font-size: 16px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    
    <div class="col-md-5">

        <div class="login-card shadow-lg">

            {{-- Icon --}}
            <div class="login-icon">
                <i class="bi bi-person-circle"></i>
            </div>

            <h3 class="text-center mb-4 fw-bold text-white">Login Admin</h3>

            {{-- Pesan error --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label class="form-label text-white fw-bold">Username</label>
                    <input type="text" name="username" class="form-control"
                           placeholder="Masukkan username" required>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label text-white fw-bold">Password</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-light w-100 mt-3 fw-bold">
                    Login
                </button>

            </form>

        </div>

    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@endsection
