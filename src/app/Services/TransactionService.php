<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    // Crea una nueva transacción y registra el evento
    // en audit_logs para trazabilidad.
    // SRP: esta clase es la única responsable de la
    // lógica de negocio sobre transacciones.
    // El controlador no sabe cómo se crea, solo pide
    // que se cree.
    public function store(array $data): Transaction
    {
        $transaction = Transaction::create([
            'user_id'     => Auth::id(),
            'category_id' => $data['category_id'] ?? null,
            'type'        => $data['type'],
            'amount'      => $data['amount'],
            'currency'    => $data['currency'] ?? 'EUR',
            'date'        => $data['date'],
            'name'        => $data['name'] ?? null,
            'merchant'    => $data['merchant'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->audit('created', $transaction, []);

        return $transaction;
    }

    // Actualiza una transacción existente y registra
    // en audit_logs exactamente qué campos cambiaron
    // y qué valores tenían antes (el diff).
    public function update(Transaction $transaction, array $data): Transaction
    {
        // Capturamos los valores originales antes de
        // modificar para poder guardar el diff.
        $original = $transaction->only(array_keys($data));

        $transaction->update([
            'category_id' => $data['category_id'] ?? null,
            'type'        => $data['type'],
            'amount'      => $data['amount'],
            'currency'    => $data['currency'] ?? 'EUR',
            'date'        => $data['date'],
            'name'        => $data['name'] ?? null,
            'merchant'    => $data['merchant'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        // Diff: solo guardamos los campos que cambiaron.
        // Formato: { campo: [valor_anterior, valor_nuevo] }
        $diff = [];
        foreach ($data as $key => $newValue) {
            if (isset($original[$key]) && (string) $original[$key] !== (string) $newValue) {
                $diff[$key] = [$original[$key], $newValue];
            }
        }

        $this->audit('updated', $transaction, $diff);

        return $transaction;
    }

    // Elimina una transacción y deja constancia en
    // audit_logs de que existió y fue eliminada,
    // guardando todos sus valores anteriores en diff.
    public function destroy(Transaction $transaction): void
    {
        $this->audit('deleted', $transaction, $transaction->toArray());

        $transaction->delete();
    }

    // Método privado que escribe en audit_logs.
    // Privado porque solo este servicio debe usarlo:
    // nada externo debería poder escribir auditorías
    // de transacciones directamente.
    private function audit(string $action, Transaction $transaction, array $diff): void
    {
        AuditLog::create([
            'action'   => $action,
            'model'    => 'Transaction',
            'model_id' => $transaction->id,
            'diff'     => $diff,
        ]);
    }
}