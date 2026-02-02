<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pengeluaran Bulanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            width: 100%;
        }

        .signature {
            width: 30%;
            float: right;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="title">
        LAPORAN REKAP PENGELUARAN BULANAN
    </div>

    <div class="subtitle">
        Sistem Informasi Pengelolaan Keuangan
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:15%">Bulan</th>
                <th style="width:25%">Kategori</th>
                <th style="width:30%">Pengeluaran per Kategori</th>
                <th style="width:30%">Total Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recap as $row)
            <tr>
                <td style="text-align:center;">
                    {{ $row->bulan }}
                </td>
                <td>
                    {{ $row->kategori }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($row->per_kategori, 0, ',', '.') }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($totalPerBulan[$row->bulan] ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">
                    Tidak ada data pengeluaran
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Indonesia, {{ date('d F Y') }}</p>
            <br><br><br>
            <p><strong>Mengetahui</strong></p>
        </div>
    </div>

</body>
</html>
