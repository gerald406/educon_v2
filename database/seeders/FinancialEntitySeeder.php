<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialEntitySeeder extends Seeder
{
    public function run(): void
    {
        $entities = [
            ['name' => 'Caja JAE (Institucional)', 'code' => 'JAE'],
            ['name' => 'Banco de la Nación', 'code' => 'BN'],
            ['name' => 'Caja Arequipa', 'code' => 'CA'],
        ];

        foreach ($entities as $entity) {
            DB::table('financial_entities')->insert([
                'name' => $entity['name'],
                'code' => $entity['code'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
