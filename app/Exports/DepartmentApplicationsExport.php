<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DepartmentApplicationsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected Department $department;

    public function __construct(Department $department)
    {
        $this->department = $department;
    }

    public function collection()
    {
        return $this->department->applications()
            ->with('application.business')
            ->get()
            ->map(function ($deptApp) {
                return [
                    $deptApp->application->reference_number,
                    $deptApp->application->business->legal_name,
                    $deptApp->application->business->ntn,
                    $deptApp->status,
                    $deptApp->progress . '%',
                    $deptApp->assignee?->name ?? 'Unassigned',
                    $deptApp->created_at?->format('Y-m-d H:i:s'),
                    $deptApp->updated_at?->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Reference Number',
            'Business Name',
            'NTN',
            'Status',
            'Progress',
            'Assigned To',
            'Created At',
            'Updated At',
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
