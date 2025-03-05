<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('coa.sql');

        if (!File::exists($path)) {
            $this->command->error("File not found: $path");
            return;
        }

        $sql = File::get($path);

        DB::unprepared($sql);
    }
}
