<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ReportService
{
    // Prepara todos los datos necesarios para el
    // informe de un período concreto (año y mes).
    // El controlador llama a este único método y
    // recibe todo lo que necesita para la vista.
    // SRP: este servicio solo sabe preparar datos
    // de informes, no sabe cómo mostrarlos.
    public function getMonthlyReport(int $year, int $month): array
    {
        $userId = Auth::id();

        $transactions = Transaction::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('category')
            ->orderBy('date')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        // Gastos agrupados por categoría para el
        // gráfico de tarta y la tabla de desglose.
        $expensesByCategory = $transactions
            ->where('type', 'expense')
            ->groupBy(fn($t) => $t->category?->name ?? 'Sin categoría')
            ->map(fn($group) => $group->sum('amount'))
            ->sortByDesc(fn($amount) => $amount);

        // Ingresos y gastos por día del mes para
        // el gráfico de líneas de evolución.
        $dailyData = $this->getDailyData($transactions, $year, $month);

        // Presupuestos del mes con su gasto real.
        $budgets = Budget::where('user_id', $userId)
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->with('category')
            ->get()
            ->map(function ($budget) {
                $budget->spent      = $budget->spentAmount();
                $budget->percentage = round($budget->spentPercentage() * 100, 1);
                return $budget;
            });

        // Top 5 gastos individuales del mes.
        $topExpenses = $transactions
            ->where('type', 'expense')
            ->sortByDesc('amount')
            ->take(5);

        return compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'expensesByCategory',
            'dailyData',
            'budgets',
            'topExpenses',
        );
    }

    // Construye los datos día a día para el gráfico
    // de evolución temporal del mes.
    // Devuelve arrays separados de labels, ingresos
    // y gastos listos para pasar a Chart.js.
    private function getDailyData(
        Collection $transactions,
        int $year,
        int $month
    ): array {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;


        $labels  = [];
        $income  = [];
        $expense = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $labels[] = $day;

            $dayTransactions = $transactions->filter(
                fn($t) => $t->date->day === $day
            );

            $income[]  = $dayTransactions->where('type', 'income')->sum('amount');
            $expense[] = $dayTransactions->where('type', 'expense')->sum('amount');
        }

        return compact('labels', 'income', 'expense');
    }
}
