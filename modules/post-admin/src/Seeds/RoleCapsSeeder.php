<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\Models\Role;

class RoleCapsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($this->checkSeeder()) {
            return;
        }
        
        $roles = Role::all();
        if (!$roles->isEmpty()) {
            foreach ($roles as $role) {
                $role->updateListCaps();
            }
        }
        
        $this->insertSeeder();
    }
}
