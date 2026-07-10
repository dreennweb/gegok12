<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\DepartmentApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Applications',
                Application::count()
            )
                ->description('All submissions')
                ->icon('heroicon-o-document-text'),
            Stat::make(
                'Pending Applications',
                DepartmentApplication::where('status', 'pending')->count()
            )
                ->description('Awaiting review')
                ->icon('heroicon-o-clock'),
            Stat::make(
                'Approved Applications',
                DepartmentApplication::where('status', 'approved')->count()
            )
                ->description('Successfully registered')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make(
                'Under Query',
                DepartmentApplication::where('status', 'queried')->count()
            )
                ->description('Action required')
                ->icon('heroicon-o-exclamation')
                ->color('warning'),
        ];
    }
}
