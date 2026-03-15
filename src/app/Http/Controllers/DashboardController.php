<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Única responsabilidad: preparar los datos
    // del resumen mensual para el dashboard.
    // Toda la lógica de consulta vive aquí,
    // la vista solo muestra lo que recibe.
    public function index()
    {
        $user  = Auth::user();
        $year  = now()->year;
        $month = now()->month;

        // Total ingresos del mes actual.
        // Suma todas las transacciones de tipo income
        // del usuario en el período actual.
        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        // Total gastos del mes actual.
        $totalExpense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        // Balance del mes: ingresos menos gastos.
        $balance = $totalIncome - $totalExpense;

        // Número de transacciones del mes actual.
        $transactionCount = Transaction::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->count();

        // Presupuestos del mes actual con su gasto real.
        // Se cargan con la relación category para poder
        // mostrar el nombre de cada categoría en la vista.
        $budgets = Budget::where('user_id', $user->id)
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->with('category')
            ->get();

        // Últimas 5 transacciones para el resumen rápido.
        $latestTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'transactionCount',
            'budgets',
            'latestTransactions',
        ));
    }
}