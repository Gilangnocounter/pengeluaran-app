@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="fw-bold mb-4 text-center">📊 Manajemen Pengeluaran</h2>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    {{-- STATISTICS --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold text-secondary">Total Pengeluaran</h5>
                    <h3 class="fw-bold text-danger">
                        Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold text-secondary">Jumlah Transaksi</h5>
                    <h3 class="fw-bold text-primary">{{ $expenses->count() }} Data</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM FILTER --}}
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white fw-bold">
            🔍 Filter Pengeluaran
        </div>

        <div class="card-body">

            <form action="{{ route('expenses.index') }}" method="GET" class="row g-3">

                {{-- Filter Kategori --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary">Kategori</label>
                    <select name="category_id" class="form-select shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tanggal Dari --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control shadow-sm"
                        value="{{ request('from') }}">
                </div>

                {{-- Filter Tanggal Hingga --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control shadow-sm"
                        value="{{ request('to') }}">
                </div>

                {{-- Tombol Filter --}}
                <div class="col-12 d-grid">
                    <button class="btn btn-primary shadow-sm">Terapkan Filter</button>
                </div>

            </form>

        </div>
    </div>

    {{-- BUTTON AKSI --}}
<div class="d-flex justify-content-between mb-3">

    {{-- Button ke Rekap Bulanan --}}
    <a href="{{ route('expenses.recap') }}" class="btn btn-outline-primary shadow-sm">
        📑 Rekap Pengeluaran Bulanan
    </a>

    {{-- Button Tambah --}}
    <a href="{{ route('expenses.create') }}" class="btn btn-success shadow-sm">
        + Tambah Pengeluaran
    </a>

</div>


    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            @if ($expenses->isEmpty())
                <div class="alert alert-warning text-center">
                    Tidak ada data pengeluaran.
                </div>

            @else
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $item)
                        <tr>
                            <td>{{ $item->category->name }}</td>
                            <td class="text-danger fw-bold">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td class="text-center">

                                {{-- Edit --}}
                                <a href="{{ route('expenses.edit', $item->id) }}"
                                    class="btn btn-warning btn-sm rounded-pill px-3">
                                    ✏️ Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('expenses.destroy', $item->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm rounded-pill px-3"
                                        onclick="return confirm('Hapus pengeluaran ini?')">
                                        🗑️ Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                <div class="mt-3">
                    {{ $expenses->links() }}
                </div>

            @endif

        </div>
    </div>

</div>
@endsection
