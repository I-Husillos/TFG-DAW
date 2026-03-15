<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    // Muestra el panel de informes con gráficos.
    // El período por defecto es el mes actual.
    public function index(Request $request)
    {
        $year  = $request->integer('year',  now()->year);
        $month = $request->integer('month', now()->month);

        $data = $this->reportService->getMonthlyReport($year, $month);

        // Lista de años para el selector de período.
        $years = range(2020, now()->year + 1);

        return view('reports.index', array_merge($data, compact('year', 'month', 'years')));
    }

    // Genera y descarga el PDF del informe mensual.
    // Usa la misma lógica de datos que index(),
    // pero renderiza una vista limpia para PDF.
    public function exportPdf(Request $request)
    {
        $year  = $request->integer('year',  now()->year);
        $month = $request->integer('month', now()->month);

        $data = $this->reportService->getMonthlyReport($year, $month);

        $pdf = Pdf::loadView('reports.pdf', array_merge($data, [
            'year'  => $year,
            'month' => $month,
        ]))->setPaper('a4', 'portrait');

        $filename = 'informe-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
