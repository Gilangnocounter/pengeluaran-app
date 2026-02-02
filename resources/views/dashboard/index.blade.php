@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ===== HEADER ===== --}}
    <div class="p-4 mb-4 rounded-3 shadow-sm text-white"
         style="background: linear-gradient(135deg, #4e73df, #224abe);">
        <h2 class="fw-bold mb-0">Dashboard Pengeluaran</h2>
        <p class="mb-0">Ringkasan keuangan bulan ini</p>
    </div>

    {{-- ===== 3 STAT CARDS ===== --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-1">Total Bulan Ini</h6>
                    <h3 class="text-danger fw-bold">
                        Rp {{ number_format($totalMonthly, 0, ',', '.') }}
                    </h3>
                    <i class="bi bi-wallet2 text-secondary fs-3 float-end"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-1">Total Kategori</h6>
                    <h3 class="fw-bold">{{ $perCategory->count() }}</h3>
                    <i class="bi bi-grid text-secondary fs-3 float-end"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-1">Transaksi Terbaru</h6>
                    <h3 class="fw-bold">{{ $recent->count() }}</h3>
                    <i class="bi bi-clock-history text-secondary fs-3 float-end"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== CHART CARD ===== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="m-0 fw-bold"><i class="bi bi-bar-chart"></i> Grafik Pengeluaran per Kategori</h5>
        </div>
        <div class="card-body">
            <canvas id="categoryChart" height="120"></canvas>
        </div>
    </div>

    {{-- ===== TABLE RECENT ===== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="m-0 fw-bold"><i class="bi bi-receipt"></i> Pengeluaran Terbaru</h5>
        </div>

        <div class="card-body">

            @if ($recent->isEmpty())
                <p>Belum ada data pengeluaran.</p>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent as $item)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $item->category->name }}</span>
                            </td>
                            <td class="fw-bold text-danger">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = {!! json_encode($perCategory->pluck('name')) !!};
    const data = {!! json_encode($perCategory->pluck('total')) !!};

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: data,
                borderWidth: 2,
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
