<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
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
                'first_name' => 'Жамбыл',
                'last_name' => 'Бекжанов',
                'middle_name' => 'Лесбекович',
                'email' => 'bekzhanov.zhambyl@cse-astana.kz',
                'password' => bcrypt('astana2017'),
                'branch_id' => 1,
                'department_id' => 0,
                'subdivision_id' => 0,
                'position_id' => 2
            ],
            [
                'first_name' => 'Галия',
                'last_name' => 'Сарсенбаева',
                'middle_name' => 'Карибаевна',
                'email' => 'sarsenbayeva.galiya@cse-astana.kz',
                'password' => bcrypt('astana2017'),
                'branch_id' => 1,
                'department_id' => 2,
                'subdivision_id' => 0,
                'position_id' => 3
            ],
            [
                'first_name' => 'Багдат',
                'last_name' => 'Тлегенов',
                'middle_name' => 'Серикович',
                'email' => 'tlegenov.bagdat@cse-astana.kz',
                'password' => bcrypt('astana2017'),
                'branch_id' => 1,
                'department_id' => 2,
                'subdivision_id' => 6,
                'position_id' => 19
            ],
            [
                'first_name' => 'Еркін',
                'last_name' => 'Таймас',
                'middle_name' => 'Дүйсенбайұлы',
                'email' => 'taimas.erkin@cse-astana.kz',
                'password' => bcrypt('astana2017'),
                'branch_id' => 1,
                'department_id' => 2,
                'subdivision_id' => 6,
                'position_id' => 6
            ],
            [
                'first_name' => 'Баглан',
                'last_name' => 'Нургожина',
                'middle_name' => 'Рамазаншариповна',
                'email' => 'nurgozhina.baglan@cse-astana.kz',
                'password' => bcrypt('astana2017'),
                'branch_id' => 1,
                'department_id' => 1,
                'subdivision_id' => 0,
                'position_id' => 4
            ]
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new User();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
