<?php

namespace App\Services\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

// Clase base para todos los QueryServices de DataTables.
// Siguiendo el mismo patrón del proyecto de tickets,
// centraliza la lógica de ordenamiento para que cada
// QueryService hijo solo defina sus campos ordenables
// y su lógica de búsqueda y filtrado específica.
// SRP: esta clase solo sabe ordenar queries.
abstract class BaseQueryService
{
    // Cada clase hija define qué columnas son ordenables
    // y a qué campo de base de datos corresponden.
    // Ejemplo: ['date' => 'date', 'amount' => 'amount']
    abstract protected function getSortableFields(): array;

    protected function getDefaultOrderField(): string
    {
        return 'created_at';
    }

    protected function getDefaultOrderDirection(): string
    {
        return 'desc';
    }

    // Aplica el ordenamiento a la query basándose en
    // los parámetros que envía DataTables automáticamente:
    // order[0][column] = índice de columna
    // order[0][dir]    = 'asc' o 'desc'
    // columns[N][data] = nombre del campo en el JSON
    protected function applyOrdering(Builder $query, Request $request): Builder
    {
        if ($request->has('order') && isset($request->input('order')[0])) {
            $order     = $request->input('order');
            $columnIdx = $order[0]['column'];
            $dir       = in_array($order[0]['dir'], ['asc', 'desc'])
                ? $order[0]['dir']
                : 'desc';
            $columnName = $request->input("columns.{$columnIdx}.data");

            $sortableFields = $this->getSortableFields();

            if (isset($sortableFields[$columnName])) {
                $query->orderBy($sortableFields[$columnName], $dir);
            } else {
                $query->orderBy($this->getDefaultOrderField(), $this->getDefaultOrderDirection());
            }
        } else {
            $query->orderBy($this->getDefaultOrderField(), $this->getDefaultOrderDirection());
        }

        return $query;
    }
}