<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Verificamos que la transacción que se quiere
        // editar pertenece al usuario autenticado.
        // $this->route('transaction') devuelve el modelo
        // Transaction resuelto por route model binding.
        return $this->route('transaction')->user_id === auth()->id();
    }

    public function rules(): array
    {
        // Las reglas son idénticas al store.
        // Se separan en dos clases por SRP: cada Request
        // tiene su propio contexto y puede evolucionar
        // independientemente.
        return [
            'type'        => ['required', 'in:income,expense'],
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
            'currency'    => ['required', 'string', 'size:3'],
            'date'        => ['required', 'date', 'before_or_equal:tomorrow'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['nullable', 'string', 'max:150'],
            'merchant'    => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'El tipo de transacción es obligatorio.',
            'type.in'              => 'El tipo debe ser ingreso o gasto.',
            'amount.required'      => 'El importe es obligatorio.',
            'amount.numeric'       => 'El importe debe ser un número.',
            'amount.min'           => 'El importe debe ser mayor que cero.',
            'currency.required'    => 'La moneda es obligatoria.',
            'currency.size'        => 'La moneda debe ser un código de 3 letras (EUR, USD…).',
            'date.required'        => 'La fecha es obligatoria.',
            'date.date'            => 'La fecha no tiene un formato válido.',
            'date.before_or_equal' => 'La fecha no puede ser futura.',
            'category_id.exists'   => 'La categoría seleccionada no existe.',
        ];
    }
}