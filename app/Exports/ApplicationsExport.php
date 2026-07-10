<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ApplicationsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    public function collection()
    {
        return Application::with('business', 'departmentApplications')
            ->get()
            ->map(function ($application) {
                return [
                    $application->reference_number,
                    $application->business->legal_name,
                    $application->business->ntn,
                    $application->business->business_type,
                    $application->status,
                    $application->global_progress . '%',
                    $application->submitted_at?->format('Y-m-d H:i:s'),
                    $application->completed_at?->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Reference Number',
            'Business Name',
            'NTN',
            'Business Type',
            'Status',
            'Progress',
            'Submitted At',
            'Completed At',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DATETIME,
            'H' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
