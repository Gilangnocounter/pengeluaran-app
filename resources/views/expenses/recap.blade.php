@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="fw-bold mb-3">📑 Rekap Pengeluaran Bulanan</h4>

    {{-- EXPORT BUTTON --}}
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('expenses.export.pdf', request()->query()) }}"
            class="btn btn-danger">
            ⬇ PDF
        </a>

        <a href="{{ route('expenses.export.excel', request()->query()) }}"
            class="btn btn-success">
            ⬇ Excel
        </a>
    </div>

    {{-- FILTER FORM --}}
    <form method="GET" id="filterForm" class="row g-2 mb-4">

        {{-- KATEGORI --}}
        <div class="col-md-3">
            <select name="category_id" class="form-select filter">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- BULAN --}}
        <div class="col-md-3">
            <select name="bulan" class="form-select filter">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}"
                        {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- TAHUN --}}
        <div class="col-md-3">
            <select name="tahun" class="form-select filter">
                <option value="">Semua Tahun</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}"
                        {{ request('tahun') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- RESET --}}
        <div class="col-md-3">
            <a href="{{ route('expenses.recap') }}"
                class="btn btn-outline-secondary w-100">
                Reset Filter
            </a>
        </div>

    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            @if($recap->isEmpty())
                <div class="alert alert-warning text-center">
                    Tidak ada data sesuai filter.
                </div>
            @else
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Bulan</th>
                            <th>Kategori</th>
                            <th>Pengeluaran per Kategori</th>
                            <th>Total Pengeluaran Bulanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recap as $row)
                        <tr>
                            <td class="text-center">{{ $row->bulan }}</td>
                            <td>{{ $row->kategori }}</td>
                            <td class="text-danger fw-bold">
                                Rp {{ number_format($row->per_kategori, 0, ',', '.') }}
                            </td>
                            <td class="fw-bold">
                                Rp {{ number_format($totalPerBulan[$row->bulan] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

</div>

{{-- AUTO SUBMIT SCRIPT --}}
<script>
    document.querySelectorAll('.filter').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endsection
