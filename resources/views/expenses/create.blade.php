@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Tambah Pengeluaran</h1>

    {{-- Error validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali input Anda:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-control"
                        placeholder="Masukkan jumlah"
                        value="{{ old('amount') }}"
                        required>
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <input type="date" name="date" class="form-control"
                        value="{{ old('date') }}"
                        required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi (opsional)</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Isi deskripsi jika diperlukan">{{ old('description') }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        Simpan Pengeluaran
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
