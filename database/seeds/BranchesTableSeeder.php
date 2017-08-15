<?php

use Illuminate\Database\Seeder;
use App\Branch;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            ['slug' => transliterate('астана'), 'name' => 'Астана', 'leader_id' => 1]
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new Branch();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
