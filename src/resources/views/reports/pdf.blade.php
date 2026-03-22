<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe financiero — {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</title>
    <style>
        /* Estilos básicos solo para el PDF.
           No usamos AdminLTE aquí porque el PDF
           se renderiza fuera del navegador. */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th {
            background: #f0f0f0;
            text-align: left;
            padding: 6px 8px;
            font-size: 11px;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }

        .summary-box .amount {
            font-size: 18px;
            font-weight: bold;
        }

        .income {
            color: #28a745;
        }

        .expense {
            color: #dc3545;
        }

        .balance {
            color: #17a2b8;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>

    <h1>Informe financiero mensual</h1>
    <p>
        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
        &nbsp;·&nbsp; Generado el {{ now()->format('d/m/Y H:i') }}
    </p>

    {{-- Resumen --}}
    <h2>Resumen del período</h2>
    <table>
        <tr>
            <th>Concepto</th>
            <th class="text-right">Importe</th>
        </tr>
        <tr>
            <td>Total ingresos</td>
            <td class="text-right income">
                + {{ number_format($totalIncome, 2, ',', '.') }} {{ user_currency() }}
            </td>
        </tr>
        <tr>
            <td>Total gastos</td>
            <td class="text-right expense">
                - {{ number_format($totalExpense, 2, ',', '.') }} {{ user_currency() }}
            </td>
        </tr>
        <tr>
            <td><strong>Balance</strong></td>
            <td class="text-right {{ $balance >= 0 ? 'income' : 'expense' }}">
                <strong>{{ number_format($balance, 2, ',', '.') }} {{ user_currency() }}</strong>
            </td>
        </tr>
    </table>

    {{-- Gastos por categoría --}}
    @if($expensesByCategory->isNotEmpty())
    <h2>Gastos por categoría</h2>
    <table>
        <tr>
            <th>Categoría</th>
            <th class="text-right">Importe</th>
            <th class="text-right">% del total</th>
        </tr>
        @foreach($expensesByCategory as $category => $amount)
        <tr>
            <td>{{ $category }}</td>
            <td class="text-right">
                {{ number_format($amount, 2, ',', '.') }} {{ user_currency() }}
            </td>
            <td class="text-right">
                {{ $totalExpense > 0
                           ? number_format(($amount / $totalExpense) * 100, 1)
                           : 0 }}%
            </td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- Top gastos --}}
    @if($topExpenses->isNotEmpty())
    <h2>Top 5 gastos del mes</h2>
    <table>
        <tr>
            <th>Concepto</th>
            <th>Categoría</th>
            <th>Fecha</th>
            <th class="text-right">Importe</th>
        </tr>
        @foreach($topExpenses as $t)
        <tr>
            <td>{{ $t->name ?? $t->merchant ?? '—' }}</td>
            <td>{{ $t->category?->name ?? '—' }}</td>
            <td>{{ $t->date->format('d/m/Y') }}</td>
            <td class="text-right expense">
                {{ number_format($t->amount, 2, ',', '.') }} {{ user_currency() }}
            </td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- Presupuestos --}}
    @if($budgets->isNotEmpty())
    <h2>Estado de presupuestos</h2>
    <table>
        <tr>
            <th>Categoría</th>
            <th class="text-right">Gastado</th>
            <th class="text-right">Límite</th>
            <th class="text-right">%</th>
        </tr>
        @foreach($budgets as $budget)
        <tr>
            <td>
                {{ $budget->category->display_name ?? $budget->category->name }}
            </td>
            <td class="text-right">
                {{ number_format($budget->spent, 2, ',', '.') }} {{ user_currency() }}
            </td>
            <td class="text-right">
                {{ number_format($budget->limit_amount, 2, ',', '.') }} {{ user_currency() }}
            </td>
            <td class="text-right {{ $budget->percentage >= 100 ? 'expense' : ($budget->percentage >= 80 ? '' : 'income') }}">
                {{ $budget->percentage }}%
            </td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- Todas las transacciones --}}
    @if($transactions->isNotEmpty())
    <h2>Detalle de transacciones</h2>
    <table>
        <tr>
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Categoría</th>
            <th>Tipo</th>
            <th class="text-right">Importe</th>
        </tr>
        @foreach($transactions as $t)
        <tr>
            <td>{{ $t->date->format('d/m/Y') }}</td>
            <td>{{ $t->name ?? $t->merchant ?? '—' }}</td>
            <td>{{ $t->category?->name ?? '—' }}</td>
            <td>{{ $t->type === 'income' ? 'Ingreso' : 'Gasto' }}</td>
            <td class="text-right {{ $t->type === 'income' ? 'income' : 'expense' }}">
                {{ $t->type === 'income' ? '+' : '-' }}
                {{ number_format($t->amount, 2, ',', '.') }} {{ user_currency() }}
            </td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="footer">
        SmartBudget · Informe generado automáticamente · {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>