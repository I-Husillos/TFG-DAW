<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    public function store(array $data): Category
    {
        $category = Category::create([
            'user_id'      => Auth::id(),
            'parent_id'    => $data['parent_id'] ?? null,
            'name'         => $data['name'],
            'display_name' => $data['display_name'] ?? null,
            'description'  => $data['description'] ?? null,
            'type'         => $data['type'],
        ]);

        AuditLog::create([
            'action'   => 'created',
            'model'    => 'Category',
            'model_id' => $category->id,
            'diff'     => [],
        ]);

        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $original = $category->only(['name', 'display_name', 'description', 'type', 'parent_id']);

        $category->update([
            'parent_id'    => $data['parent_id'] ?? null,
            'name'         => $data['name'],
            'display_name' => $data['display_name'] ?? null,
            'description'  => $data['description'] ?? null,
            'type'         => $data['type'],
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
            'model'    => 'Category',
            'model_id' => $category->id,
            'diff'     => $diff,
        ]);

        return $category;
    }

    public function destroy(Category $category): void
    {
        AuditLog::create([
            'action'   => 'deleted',
            'model'    => 'Category',
            'model_id' => $category->id,
            'diff'     => $category->toArray(),
        ]);

        $category->delete();
    }
}