<?php

namespace App\Services\DataTables;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetQueryService extends BaseQueryService
{
    protected function getSortableFields(): array
    {
        return [
            'category'    => 'category_id',
            'period'      => 'period_year',
            'limit'       => 'limit_amount',
        ];
    }

    public function buildQuery(Request $request)
    {
        $query = Budget::where('user_id', Auth::id())
            ->with('category');

        // Filtro por año y mes para ver un período concreto
        if ($request->filled('year')) {
            $query->where('period_year', $request->input('year'));
        }

        if ($request->filled('month')) {
            $query->where('period_month', $request->input('month'));
        }

        $search = $request->input('search.value');
        if ($search) {
            $query->whereHas('category', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('display_name', 'LIKE', "%{$search}%");
            });
        }

        return $this->applyOrdering($query, $request);
    }

    public function totalCount(): int
    {
        return Budget::where('user_id', Auth::id())->count();
    }
}