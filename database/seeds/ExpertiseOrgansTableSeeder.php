<?php

use Illuminate\Database\Seeder;
use App\ExpertiseOrgan;

class ExpertiseOrgansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            [ 'name' => 'Суды'],
            [ 'name' => 'Прокуратура'],
            [ 'name' => 'Орган внутренних дел'],
            [ 'name' => 'Комитет Национальной Безопасности'],
            [ 'name' => 'Национальное бюро по противодействию коррупции'],
            [ 'name' => 'Комитет государственных доходов'],
            [ 'name' => 'Военно – следственный департамент МВД РК'],
            [ 'name' => 'Адвокатура'],
            [ 'name' => 'Следственный судья'],
            [ 'name' => 'Прочие']
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new ExpertiseOrgan();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
