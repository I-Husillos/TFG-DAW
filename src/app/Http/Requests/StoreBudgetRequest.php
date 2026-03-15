<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                // La categoría debe existir y pertenecer al usuario.
                Rule::exists('categories', 'id')->where(
                    fn($query) => $query->where('user_id', auth()->id())
                ),
            ],
            'period_year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],
            'period_month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
            'limit_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'alert_threshold' => [
                'nullable',
                'numeric',
                'min:0.01',
                'max:1',
            ],
            // Unique compuesto: no puede haber dos presupuestos
            // para la misma categoría en el mismo mes.
            // Se valida aquí además de en la migración para
            // dar un mensaje de error claro al usuario.
            '_unique_budget' => [
                Rule::unique('budgets')
                    ->where(
                        fn($query) => $query
                            ->where('user_id', auth()->id())
                            ->where('category_id', $this->category_id)
                            ->where('period_year', $this->period_year)
                            ->where('period_month', $this->period_month)
                    ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'    => 'La categoría es obligatoria.',
            'category_id.exists'      => 'La categoría seleccionada no existe.',
            'period_year.required'    => 'El año es obligatorio.',
            'period_year.min'         => 'El año debe ser mayor que 2000.',
            'period_month.required'   => 'El mes es obligatorio.',
            'period_month.min'        => 'El mes debe estar entre 1 y 12.',
            'period_month.max'        => 'El mes debe estar entre 1 y 12.',
            'limit_amount.required'   => 'El importe límite es obligatorio.',
            'limit_amount.min'        => 'El importe límite debe ser mayor que cero.',
            'alert_threshold.min'     => 'El umbral de alerta debe ser mayor que 0.',
            'alert_threshold.max'     => 'El umbral de alerta no puede superar 1 (100%).',
            '_unique_budget'          => 'Ya existe un presupuesto para esta categoría en ese período.',
        ];
    }
}
