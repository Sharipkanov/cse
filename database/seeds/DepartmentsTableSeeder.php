<?php

use Illuminate\Database\Seeder;
use App\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            [
                'name' => 'АУП',
                'parent_id' => 0,
                'branch_id' => 1,
                'leader_id' => 0
            ],
            [
                'name' => 'Отдел по судебно-экспертным вопросам',
                'parent_id' => 0,
                'branch_id' => 1,
                'leader_id' => 2
            ],
            [
                'name' => 'Отдел по судебно-медицинским вопросам',
                'parent_id' => 0,
                'branch_id' => 1,
                'leader_id' => 0
            ],
            [
                'name' => 'Отдел по общим вопросам',
                'parent_id' => 0,
                'branch_id' => 1,
                'leader_id' => 0
            ],
            [
                'name' => 'Лаборатория химических и биологических исследований',
                'parent_id' => 2,
                'branch_id' => 1,
                'leader_id' => 0
            ],
            [
                'name' => 'Лаборатория криминалистических исследований',
                'parent_id' => 2,
                'branch_id' => 1,
                'leader_id' => 3
            ],
            [
                'name' => 'Лаборатория специальных исследований',
                'parent_id' => 2,
                'branch_id' => 1,
                'leader_id' => 0
            ]
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new Department();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
