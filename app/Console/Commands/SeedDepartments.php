<?php

namespace App\Console\Commands;

use App\Models\Department;
use Illuminate\Console
Command;

class SeedDepartments extends Command
{
    protected $signature = 'app:seed-departments';
    protected $description = 'Seed initial departments for Balochistan Registration Portal';

    public function handle()
    {
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\DepartmentSeeder']);
        $this->info('Departments seeded successfully!');
    }
}
