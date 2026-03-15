<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('budget')->user_id === auth()->id();
    }

    public function rules(): array
    {
        $budget = $this->route('budget');

        return [
            'category_id' => [
                'required',
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
            // Al actualizar ignoramos el propio registro
            // para que no falle si no cambia el período.
            '_unique_budget' => [
                Rule::unique('budgets')
                    ->where(
                        fn($query) => $query
                            ->where('user_id', auth()->id())
                            ->where('category_id', $this->category_id)
                            ->where('period_year', $this->period_year)
                            ->where('period_month', $this->period_month)
                    )
                    ->ignore($budget->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'  => 'La categoría es obligatoria.',
            'category_id.exists'    => 'La categoría seleccionada no existe.',
            'period_year.required'  => 'El año es obligatorio.',
            'period_month.required' => 'El mes es obligatorio.',
            'period_month.min'      => 'El mes debe estar entre 1 y 12.',
            'period_month.max'      => 'El mes debe estar entre 1 y 12.',
            'limit_amount.required' => 'El importe límite es obligatorio.',
            'limit_amount.min'      => 'El importe límite debe ser mayor que cero.',
            '_unique_budget'        => 'Ya existe un presupuesto para esta categoría en ese período.',
        ];
    }
}
