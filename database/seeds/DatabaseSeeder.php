<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(BranchesTableSeeder::class);
         $this->call(PositionsTableSeeder::class);
         $this->call(LanguagesTableSeeder::class);
         $this->call(DocumentTypesTableSeeder::class);
         $this->call(DepartmentsTableSeeder::class);
         $this->call(NomenclaturesTableSeeder::class);
         $this->call(ExpertiseCategoriesTableSeeder::class);
         $this->call(ExpertiseRegionsTableSeeder::class);
         $this->call(ExpertiseAgenciesTableSeeder::class);
         $this->call(ExpertiseTypesTableSeeder::class);
         $this->call(ExpertiseSpecialitiesTableSeeder::class);
         $this->call(ExpertiseStatusesTableSeeder::class);
         $this->call(ExpertiseOrgansTableSeeder::class);
         $this->call(CorrespondentsTableSeeder::class);
    }
}
