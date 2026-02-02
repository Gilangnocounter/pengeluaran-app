<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseMonthlyExport implements FromCollection, WithHeadings
{
    protected $userId;
    protected $categoryId;
    protected $bulan;
    protected $tahun;

    public function __construct($userId, $categoryId = null, $bulan = null, $tahun = null)
    {
        $this->userId     = $userId;
        $this->categoryId = $categoryId;
        $this->bulan      = $bulan;
        $this->tahun      = $tahun;
    }

    public function collection()
    {
        /* =========================
         * SUBQUERY: PER KATEGORI
         * ========================= */
        $perKategori = DB::table('expenses')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where('expenses.user_id', $this->userId)
            ->when($this->categoryId, function ($q) {
                $q->where('expenses.category_id', $this->categoryId);
            })
            ->when($this->bulan, function ($q) {
                $q->whereMonth('expenses.date', $this->bulan);
            })
            ->when($this->tahun, function ($q) {
                $q->whereYear('expenses.date', $this->tahun);
            })
            ->select(
                DB::raw("DATE_FORMAT(expenses.date, '%Y-%m') as bulan"),
                'categories.name as kategori',
                DB::raw('SUM(expenses.amount) as pengeluaran_per_kategori')
            )
            ->groupBy('bulan', 'kategori');

        /* =========================
         * SUBQUERY: TOTAL PER BULAN
         * ========================= */
        $totalPerBulan = DB::table('expenses')
            ->where('user_id', $this->userId)
            ->when($this->categoryId, function ($q) {
                $q->where('category_id', $this->categoryId);
            })
            ->when($this->bulan, function ($q) {
                $q->whereMonth('date', $this->bulan);
            })
            ->when($this->tahun, function ($q) {
                $q->whereYear('date', $this->tahun);
            })
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as bulan"),
                DB::raw('SUM(amount) as total_pengeluaran')
            )
            ->groupBy('bulan');

        /* =========================
         * FINAL QUERY
         * ========================= */
        return DB::query()
            ->fromSub($perKategori, 'pk')
            ->joinSub($totalPerBulan, 'tb', function ($join) {
                $join->on('pk.bulan', '=', 'tb.bulan');
            })
            ->select(
                'pk.bulan as Bulan',
                'pk.kategori as Kategori',
                'pk.pengeluaran_per_kategori as Pengeluaran_per_Kategori',
                'tb.total_pengeluaran as Total_Pengeluaran'
            )
            ->orderBy('pk.bulan', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Kategori',
            'Pengeluaran per Kategori',
            'Total Pengeluaran'
        ];
    }
}