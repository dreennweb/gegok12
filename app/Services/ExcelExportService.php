<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Department;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

class ExcelExportService
{
    /**
     * Export all applications for superadmin
     */
    public function exportAllApplications()
    {
        return Excel::download(
            new \App\Exports\ApplicationsExport(),
            'all_applications_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Export department-specific applications
     */
    public function exportDepartmentApplications(Department $department)
    {
        return Excel::download(
            new \App\Exports\DepartmentApplicationsExport($department),
            $department->slug . '_applications_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}
