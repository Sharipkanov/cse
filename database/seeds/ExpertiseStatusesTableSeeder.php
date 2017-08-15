<?php

use Illuminate\Database\Seeder;
use App\ExpertiseStatus;

class ExpertiseStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['name' => 'Первичная', 'primary' => 1],
            ['name' => 'Дополнительная', 'primary' => 1],
            ['name' => 'Повторная', 'primary' => 1],
            ['name' => 'Единоличная', 'primary' => 0],
            ['name' => 'Комплексная', 'primary' => 0],
            ['name' => 'Комиссионная', 'primary' => 0]
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new ExpertiseStatus();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
