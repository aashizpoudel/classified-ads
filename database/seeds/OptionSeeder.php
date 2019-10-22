<?php

use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         //Insert country first
         $option_sql = file_get_contents(__DIR__ . '/dumps/options.sql');
         // split the statements, so DB::statement can execute them.
         $option_statements = array_filter(array_map('trim', explode(';', $option_sql)));
         foreach ($option_statements as $stmt) {
             DB::statement($stmt);
         }
    }
}
