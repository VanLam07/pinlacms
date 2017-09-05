<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\User;

class UserSeeder extends BaseSeeder
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
        
        $admin = User::create(['id' => 1, 'name' => 'Admin', 'slug' => 'admin', 'email' => 'admin@gmail.com', 'password' => bcrypt('admin')]);
        $editor = User::create(['id' => 2, 'name' => 'Editor', 'slug' => 'editor', 'email' => 'editor@gmail.com', 'password' => bcrypt('editor')]);
        $member = User::create(['id' => 3, 'name' => 'Member', 'slug' => 'member', 'email' => 'member@gmail.com', 'password' => bcrypt('member')]);
        
        $admin->roles()->attach(1);
        $editor->roles()->attach(2);
        $member->roles()->attach(3);
        
        $this->insertSeeder();
    }
}
