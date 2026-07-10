<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Balochistan Revenue Authority',
                'slug' => 'bra',
                'code' => 'BRA',
                'description' => 'Sales Tax, Income Tax, and Worker Welfare Fund registration',
                'contact_email' => 'info@bra.balochistan.gov.pk',
                'contact_phone' => '+92-81-9211234',
            ],
            [
                'name' => 'Labour Department',
                'slug' => 'labour',
                'code' => 'LABOUR',
                'description' => 'Worker registration and labour compliance',
                'contact_email' => 'info@labour.balochistan.gov.pk',
                'contact_phone' => '+92-81-9212345',
            ],
            [
                'name' => 'Excise Department',
                'slug' => 'excise',
                'code' => 'EXCISE',
                'description' => 'Excise and intoxicated substance registration',
                'contact_email' => 'info@excise.balochistan.gov.pk',
                'contact_phone' => '+92-81-9213456',
            ],
            [
                'name' => 'Balochistan Food Authority',
                'slug' => 'bfa',
                'code' => 'BFA',
                'description' => 'Food safety and business registration',
                'contact_email' => 'info@bfa.balochistan.gov.pk',
                'contact_phone' => '+92-81-9214567',
            ],
            [
                'name' => 'Balochistan Healthcare Commission',
                'slug' => 'bhc',
                'code' => 'BHC',
                'description' => 'Healthcare facility registration and licensing',
                'contact_email' => 'info@bhc.balochistan.gov.pk',
                'contact_phone' => '+92-81-9215678',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
