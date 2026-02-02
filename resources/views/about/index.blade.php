@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="text-center mb-4 fw-bold">About Us</h1>

    <p class="text-center mb-5 text-muted fs-5">
        Kami adalah tim pengembang aplikasi pengelolaan pengeluaran yang bertujuan 
        membantu Anda mengatur keuangan pribadi secara lebih mudah, rapi, dan efisien.
    </p>

    <div class="row justify-content-center">

        @foreach ($team as $member)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="team-card p-4 text-center">

                {{-- Foto Profile --}}
                <img src="{{ $member['photo'] ?? 'https://via.placeholder.com/200?text=Photo' }}"
                     class="profile-photo mb-3" alt="Foto">

                {{-- Nama --}}
                <h5 class="fw-bold mb-1">{{ $member['name'] }}</h5>

                {{-- Role/Desc --}}
                <p class="text-muted">{{ $member['desc'] }}</p>

            </div>
        </div>
        @endforeach

    </div>

</div>

{{-- Custom CSS --}}
<style>
    .team-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        transition: .3s;
        border: none;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.12);
    }

    /* Foto bulat */
    .profile-photo {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #f0f0f0;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
    }
</style>

@endsection
