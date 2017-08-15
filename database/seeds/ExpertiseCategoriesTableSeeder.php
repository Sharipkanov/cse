<?php

use Illuminate\Database\Seeder;
use App\ExpertiseCategory;

class ExpertiseCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['name' => 'Уголовное'],
            ['name' => 'Гравжданское'],
            ['name' => 'Административное'],
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new ExpertiseCategory();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
