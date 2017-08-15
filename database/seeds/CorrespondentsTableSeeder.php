<?php

use Illuminate\Database\Seeder;
use App\Correspondent;

class CorrespondentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['name' => 'Верховный суд'],
            ['name' => 'Прокуратура']
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new Correspondent();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
