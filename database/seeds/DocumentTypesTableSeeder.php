<?php

use Illuminate\Database\Seeder;
use App\DocumentType;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['name' => 'Письмо'],
            ['name' => 'Ходатайство'],
            ['name' => 'Ответ на ходатайство'],
            ['name' => 'Телефонограмма']
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new DocumentType();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
