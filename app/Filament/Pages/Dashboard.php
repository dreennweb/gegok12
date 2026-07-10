<?php

namespace App\Filament\Pages;

use App\Models\Application;
use App\Models\Department;
use App\Models\DepartmentApplication;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            DashboardStats::class,
        ];
    }
}
