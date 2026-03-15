<?php

namespace App\Services\DataTables;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


// Construye la query de transacciones para DataTables.
// Única responsabilidad: aplicar filtros, búsqueda
// y ordenamiento sobre la tabla transactions.
// El controlador no sabe cómo se construye la query,
// solo pide los resultados.
class TransactionQueryService extends BaseQueryService
{
    protected function getSortableFields(): array
    {
        return [
            'date'     => 'date',
            'name'     => 'name',
            'category' => 'category_id',
            'type'     => 'type',
            'amount'   => 'amount',
        ];
    }

    protected function getDefaultOrderField(): string
    {
        return 'date';
    }

    public function buildQuery(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with('category');

        // Filtro por tipo (income / expense)
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filtro por rango de fechas
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        // Búsqueda global de DataTables (search[value])
        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('merchant', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $this->applyOrdering($query, $request);
    }

    public function totalCount(): int
    {
        return Transaction::where('user_id', Auth::id())->count();
    }
}
