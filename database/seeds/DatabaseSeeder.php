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
        DB::beginTransaction();
        try {
            $this->call(Admin\Seeds\LangSeeder::class);
            $this->call(Admin\Seeds\RoleSeeder::class);
            $this->call(Admin\Seeds\UserSeeder::class);
            $this->call(Admin\Seeds\CapSeeder::class);
            $this->call(Admin\Seeds\OptionSeeder::class);
            $this->call(Admin\Seeds\RoleCapsSeeder::class);
            
            //$this->call(Dict\Seeds\DictSeeder::class);
            //$this->call(Dict\Seeds\DictOriginSeeder::class);
            //$this->call(Dict\Seeds\Words3000Seeder::class);
            
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
}
