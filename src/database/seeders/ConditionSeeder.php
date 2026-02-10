<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ConditionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $names = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い',
        ];

        $rows = array_map(fn ($name) => [
            'name' => $name,
            'created_at' => $now,
            'updated_at' => $now,
        ], $names);

        DB::table('conditions')->upsert($rows, ['name'], ['updated_at']);
    }
}