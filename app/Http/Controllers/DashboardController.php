<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // total pengeluaran bulan ini
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $totalMonthly = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // pengeluaran per kategori (untuk grafik)
        $perCategory = Expense::selectRaw('categories.name, SUM(expenses.amount) as total')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where('expenses.user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->groupBy('categories.name')
            ->get();

        // daftar terbaru
        $recent = Expense::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('totalMonthly', 'perCategory', 'recent'));
    }
}
