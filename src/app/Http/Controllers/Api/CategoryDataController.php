<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\DataTables\CategoryQueryService;
use Illuminate\Http\Request;

class CategoryDataController extends Controller
{
    public function __construct(
        private CategoryQueryService $queryService
    ) {}

    public function index(Request $request)
    {
        $query    = $this->queryService->buildQuery($request);
        $total    = $this->queryService->totalCount();
        $filtered = $query->count();

        $categories = $query
            ->skip($request->input('start', 0))
            ->take($request->input('length', 15))
            ->get();

        $data = $categories->map(fn($c) => $this->transform($c));

        return response()->json([
            'draw'            => (int) $request->input('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    private function transform(Category $c): array
    {
        return [
            'name'          => $c->display_name ?? $c->name,
            'type'          => $c->type === 'income' ? 'Ingreso' : 'Gasto',
            'type_raw'      => $c->type,
            'subcategories' => $c->children->pluck('name')->join(', ') ?: '—',
            'description'   => $c->description ?? '—',
            'actions'       => $this->actions($c),
        ];
    }

    private function actions(Category $c): string
    {
        $editUrl    = route('categories.edit', $c);
        $destroyUrl = route('categories.destroy', $c);
        $csrf       = csrf_token();

        return <<<HTML
            <a href="{$editUrl}" class="btn btn-xs btn-warning" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{$destroyUrl}" method="POST" class="d-inline"
                  onsubmit="return confirm('¿Eliminar esta categoría?')">
                <input type="hidden" name="_token" value="{$csrf}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-xs btn-danger" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        HTML;
    }
}