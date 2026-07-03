<?php

namespace App\Http\Controllers\Concerns;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

trait ExportsResource
{
    /**
     * @param  class-string  $exportClass
     */
    protected function exportExcel(string $exportClass, array $filters, string $filename): BinaryFileResponse
    {
        return Excel::download(new $exportClass($filters), $filename);
    }

    protected function exportPdf(string $view, array $data, string $filename, string $paper = 'a4', string $orientation = 'portrait'): Response
    {
        return Pdf::loadView($view, $data)
            ->setPaper($paper, $orientation)
            ->download($filename);
    }

    protected function streamPdf(string $view, array $data, string $paper = 'a4', string $orientation = 'portrait'): Response
    {
        return Pdf::loadView($view, $data)
            ->setPaper($paper, $orientation)
            ->stream();
    }

    protected function printView(string $view, array $data): Response
    {
        return response()->view($view, array_merge($data, ['isPrint' => true]));
    }
}
