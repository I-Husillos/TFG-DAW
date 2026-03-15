<?php

namespace App\Services\DataTables;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryQueryService extends BaseQueryService
{
    protected function getSortableFields(): array
    {
        return [
            'name' => 'name',
            'type' => 'type',
        ];
    }

    public function buildQuery(Request $request)
    {
        // Solo categorías de primer nivel con sus hijos
        $query = Category::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->with('children');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('display_name', 'LIKE', "%{$search}%");
            });
        }

        return $this->applyOrdering($query, $request);
    }

    public function totalCount(): int
    {
        return Category::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->count();
    }
}