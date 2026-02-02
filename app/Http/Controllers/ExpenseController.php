<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\ExpenseMonthlyExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        $query = Expense::with('category')
            ->where('user_id', Auth::id())
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        $expenses   = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    /* =========================
     * CREATE & STORE
     * ========================= */
    public function create()
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran ditambahkan.');
    }

    /* =========================
     * EDIT & UPDATE
     * ========================= */
    public function edit(Expense $expense)
    {
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran diperbarui.');
    }

    /* =========================
     * DELETE
     * ========================= */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran dihapus.');
    }

    /* =========================
     * REKAP BULANAN (FILTERABLE)
     * ========================= */
    public function monthlyRecap(Request $request)
    {
        $userId = Auth::id();

        $categoryId = $request->category_id;
        $bulan      = $request->bulan;
        $tahun      = $request->tahun;

        /* === QUERY REKAP === */
        $query = DB::table('expenses')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where('expenses.user_id', $userId);

        if ($categoryId) {
            $query->where('expenses.category_id', $categoryId);
        }

        if ($bulan) {
            $query->whereMonth('expenses.date', $bulan);
        }

        if ($tahun) {
            $query->whereYear('expenses.date', $tahun);
        }

        $recap = $query
            ->select(
                DB::raw("DATE_FORMAT(expenses.date, '%Y-%m') as bulan"),
                'categories.name as kategori',
                DB::raw('SUM(expenses.amount) as per_kategori')
            )
            ->groupBy('bulan', 'kategori')
            ->orderBy('bulan', 'desc')
            ->get();

        /* === TOTAL PER BULAN === */
        $totalPerBulan = DB::table('expenses')
            ->where('user_id', $userId)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($bulan, fn ($q) => $q->whereMonth('date', $bulan))
            ->when($tahun, fn ($q) => $q->whereYear('date', $tahun))
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as bulan"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $categories = Category::all();

        return view('expenses.recap', compact(
            'recap',
            'totalPerBulan',
            'categories'
        ));
    }

    /* =========================
     * EXPORT EXCEL (IKUT FILTER)
     * ========================= */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new ExpenseMonthlyExport(
                Auth::id(),
                $request->category_id,
                $request->bulan,
                $request->tahun
            ),
            'rekap_pengeluaran_bulanan.xlsx'
        );
    }

    /* =========================
     * EXPORT PDF (IKUT FILTER)
     * ========================= */
    public function exportPdf(Request $request)
    {
        $userId = Auth::id();

        $categoryId = $request->category_id;
        $bulan      = $request->bulan;
        $tahun      = $request->tahun;

        $query = DB::table('expenses')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where('expenses.user_id', $userId);

        if ($categoryId) {
            $query->where('expenses.category_id', $categoryId);
        }

        if ($bulan) {
            $query->whereMonth('expenses.date', $bulan);
        }

        if ($tahun) {
            $query->whereYear('expenses.date', $tahun);
        }

        $recap = $query
            ->select(
                DB::raw("DATE_FORMAT(expenses.date, '%Y-%m') as bulan"),
                'categories.name as kategori',
                DB::raw('SUM(expenses.amount) as per_kategori')
            )
            ->groupBy('bulan', 'kategori')
            ->orderBy('bulan', 'desc')
            ->get();

        $totalPerBulan = DB::table('expenses')
            ->where('user_id', $userId)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($bulan, fn ($q) => $q->whereMonth('date', $bulan))
            ->when($tahun, fn ($q) => $q->whereYear('date', $tahun))
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as bulan"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $pdf = Pdf::loadView('expenses.recap_pdf', compact(
            'recap',
            'totalPerBulan'
        ));

        return $pdf->download('rekap_pengeluaran_bulanan.pdf');
    }
}
