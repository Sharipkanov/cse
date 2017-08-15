<?php

use Illuminate\Database\Seeder;
use App\ExpertiseRegion;

class ExpertiseRegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['name' => 'г. Астана'],
            ['name' => 'г. Алматы'],
            ['name' => 'Акмолинская обл.'],
            ['name' => 'Актюбинская обл.'],
            ['name' => 'Алматинская  обл.'],
            ['name' => 'Атырауская обл.'],
            ['name' => 'Западно-Казахстанская обл.'],
            ['name' => 'Жамбылская обл.'],
            ['name' => 'Карагандинская обл.'],
            ['name' => 'Костанайская обл.'],
            ['name' => 'Кызылординская обл.'],
            ['name' => 'Мангистауская обл.'],
            ['name' => 'Южно-Казахстанская обл.'],
            ['name' => 'Павлодарская обл.'],
            ['name' => 'Северо-Казахстанская обл.'],
            ['name' => 'Восточно-Казахстанская обл.']
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new ExpertiseRegion();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
