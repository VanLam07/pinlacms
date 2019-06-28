<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\Models\Role;

class RoleSeeder extends BaseSeeder
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
        
        Role::create(['id' => 1, 'label' => 'Administrator', 'name' => 'administrator', 'default' => 0]);
        Role::create(['id' => 2, 'label' => 'Editor', 'name' => 'editor', 'default' => 0]);
        Role::create(['id' => 3, 'label' => 'Member', 'name' => 'member', 'default' => 1]);
        
        $this->insertSeeder();
    }
}
