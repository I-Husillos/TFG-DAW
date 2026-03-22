<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

/**
 * DefaultCategoryService
 *
 * Única responsabilidad: crear el conjunto de categorías
 * predeterminadas para un usuario recién registrado.
 *
 * Por qué en un servicio y no en el controlador:
 *   - El controlador solo debe orquestar: recibir la
 *     petición, validarla y devolver la respuesta.
 *   - La lógica de "qué categorías existen por defecto"
 *     puede evolucionar (añadir, quitar, traducir) sin
 *     tocar el flujo de registro.
 */
class DefaultCategoryService
{
    /**
     * Crea las categorías predeterminadas para un usuario.
     *
     * @param  User  $user  El usuario recién creado.
     * @return void
     */
    public function createFor(User $user): void
    {
        // Cada entrada del array es un array con los
        // campos que necesita el modelo Category.
        $incomeCategories = [
            ['name' => 'Salario',          'display_name' => 'Salario'],
            ['name' => 'Freelance',        'display_name' => 'Freelance'],
            ['name' => 'Alquiler cobrado', 'display_name' => 'Alquiler cobrado'],
            ['name' => 'Inversiones',      'display_name' => 'Inversiones'],
            ['name' => 'Otros ingresos',   'display_name' => 'Otros ingresos'],
        ];

        foreach ($incomeCategories as $data) {
            Category::create([
                'user_id'      => $user->id,
                'name'         => $data['name'],
                'display_name' => $data['display_name'],
                'type'         => 'income',
            ]);
        }

        $expenseCategories = [
            ['name' => 'Alimentación', 'display_name' => 'Alimentación'],
            ['name' => 'Transporte',   'display_name' => 'Transporte'],
            ['name' => 'Vivienda',     'display_name' => 'Vivienda'],
            ['name' => 'Salud',        'display_name' => 'Salud'],
            ['name' => 'Educación',    'display_name' => 'Educación'],
            ['name' => 'Ocio',         'display_name' => 'Ocio y entretenimiento'],
            ['name' => 'Ropa',         'display_name' => 'Ropa y calzado'],
            ['name' => 'Tecnología',   'display_name' => 'Tecnología'],
            ['name' => 'Seguros',      'display_name' => 'Seguros'],
            ['name' => 'Otros gastos', 'display_name' => 'Otros gastos'],
        ];

        foreach ($expenseCategories as $data) {
            Category::create([
                'user_id'      => $user->id,
                'name'         => $data['name'],
                'display_name' => $data['display_name'],
                'type'         => 'expense',
            ]);
        }

        // Necesitamos el ID del padre para enlazarlas.
        // firstWhere busca en la colección en memoria
        // (más eficiente que otra query a la BD).
        $transporte = Category::where('user_id', $user->id)
            ->where('name', 'Transporte')
            ->first();

        if ($transporte) {
            $subcats = [
                'Gasolina',
                'Transporte público',
                'Parking',
                'Mantenimiento vehículo',
            ];

            foreach ($subcats as $nombre) {
                Category::create([
                    'user_id'   => $user->id,
                    'parent_id' => $transporte->id,
                    'name'      => $nombre,
                    'type'      => 'expense',
                ]);
            }
        }

        $vivienda = Category::where('user_id', $user->id)
            ->where('name', 'Vivienda')
            ->first();

        if ($vivienda) {
            $subcats = [
                'Alquiler',
                'Hipoteca',
                'Suministros',
                'Comunidad',
            ];

            foreach ($subcats as $nombre) {
                Category::create([
                    'user_id'   => $user->id,
                    'parent_id' => $vivienda->id,
                    'name'      => $nombre,
                    'type'      => 'expense',
                ]);
            }
        }
    }
}