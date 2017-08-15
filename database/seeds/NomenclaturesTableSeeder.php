<?php

use Illuminate\Database\Seeder;
use App\Nomenclature;

class NomenclaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['name' => 'Руководство', 'code' => '01-01'],
            ['name' => 'Бухгалтерия', 'code' => '02-01'],
            ['name' => 'Отдел кадров', 'code' => '03-01'],
            ['name' => 'АУП', 'code' => '04-01'],
            ['name' => 'Отдел сложных экспертиз', 'code' => '05-01'],
            ['name' => 'Отдел по судебно-экспертным вопросам', 'code' => '06-01'],
            ['name' => 'Отдел по судебно-медицинским вопросам', 'code' => '07-01'],
            ['name' => 'Отдел по общим вопросам', 'code' => '08-01'],
            ['name' => 'Канцелярия', 'code' => '09-01'],
            ['name' => 'Исходящая корреспонденция', 'code' => '10-01']
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new Nomenclature();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
