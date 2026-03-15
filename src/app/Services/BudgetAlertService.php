<?php

namespace App\Services;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

// Única responsabilidad: calcular qué presupuestos
// del mes actual han superado su umbral de alerta.
// Se usa tanto en la navbar como en el dashboard
// para mostrar alertas visuales al usuario.
// No usa colas ni emails porque en monousuario
// la alerta es siempre para el propio usuario
// que está navegando la app.
class BudgetAlertService
{
    // Devuelve los presupuestos del mes actual
    // que han superado su alert_threshold.
    // Se llama desde el ViewServiceProvider para
    // que esté disponible en todas las vistas.
    public function getActiveAlerts(): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        return Budget::where('user_id', Auth::id())
            ->where('period_year', now()->year)
            ->where('period_month', now()->month)
            ->with('category')
            ->get()
            ->filter(fn($budget) => $budget->hasReachedThreshold())
            ->map(function ($budget) {
                $spent      = $budget->spentAmount();
                $percentage = round($budget->spentPercentage() * 100, 1);

                return [
                    'budget'     => $budget,
                    'spent'      => $spent,
                    'percentage' => $percentage,
                    'exceeded'   => $percentage >= 100,
                    'category'   => $budget->category->display_name
                                    ?? $budget->category->name,
                ];
            })
            ->values();
    }

    // Devuelve solo el conteo para el badge de la navbar.
    public function getAlertCount(): int
    {
        return $this->getActiveAlerts()->count();
    }
}