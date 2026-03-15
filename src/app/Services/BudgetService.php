<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetService
{
    // Crea un presupuesto mensual para una categoría.
    // La restricción unique (user_id, category_id,
    // period_year, period_month) ya está en la migración,
    // pero el Request también lo valida antes de llegar aquí.
    public function store(array $data): Budget
    {
        $budget = Budget::create([
            'user_id'         => Auth::id(),
            'category_id'     => $data['category_id'],
            'period_year'     => $data['period_year'],
            'period_month'    => $data['period_month'],
            'limit_amount'    => $data['limit_amount'],
            'alert_threshold' => $data['alert_threshold'] ?? 0.80,
        ]);

        AuditLog::create([
            'action'   => 'created',
            'model'    => 'Budget',
            'model_id' => $budget->id,
            'diff'     => [],
        ]);

        return $budget;
    }

    public function update(Budget $budget, array $data): Budget
    {
        $original = $budget->only([
            'category_id',
            'period_year',
            'period_month',
            'limit_amount',
            'alert_threshold',
        ]);

        $budget->update([
            'category_id'     => $data['category_id'],
            'period_year'     => $data['period_year'],
            'period_month'    => $data['period_month'],
            'limit_amount'    => $data['limit_amount'],
            'alert_threshold' => $data['alert_threshold'] ?? 0.80,
        ]);

        $diff = [];
        foreach ($original as $key => $oldValue) {
            $newValue = $data[$key] ?? null;
            if ((string) $oldValue !== (string) $newValue) {
                $diff[$key] = [$oldValue, $newValue];
            }
        }

        AuditLog::create([
            'action'   => 'updated',
            'model'    => 'Budget',
            'model_id' => $budget->id,
            'diff'     => $diff,
        ]);

        return $budget;
    }

    public function destroy(Budget $budget): void
    {
        AuditLog::create([
            'action'   => 'deleted',
            'model'    => 'Budget',
            'model_id' => $budget->id,
            'diff'     => $budget->toArray(),
        ]);

        $budget->delete();
    }
}
