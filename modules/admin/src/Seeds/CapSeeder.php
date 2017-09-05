<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\Models\Cap;

class CapSeeder extends BaseSeeder
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
        // name => [[role_ids]]
        $caps = [
            'view_posts' => [[1, 2, 3]],
            'publish_posts' => [[1, 2, 3]], 
            'edit_my_post' => [[1, 2, 3]],  
            'edit_other_posts' => [[1, 2]], 
            'remove_my_post' => [[1, 2, 3]],
            'remove_other_posts' => [[1, 2]], 
            
            'manage_roles' => [[1]], 
            
            'manage_caps' => [[1]], 
            
            'view_users' => [[1]],
            'publish_users' => [[1]], 
            'edit_my_user' => [[1, 2, 3]], 
            'edit_other_users' => [[1]], 
            'remove_my_user' => [[1, 2, 3]], 
            'remove_other_users' => [[1]], 
            
            'accept_manage' => [[1]],
            
            'manage_langs' => [[1]], 
            
            'manage_cats' => [[1]], 
            
            'manage_tags' => [[1, 2]], 
             
            'view_comments' => [[1, 2, 3]],
            'publish_comments' => [[1, 2, 3]], 
            'edit_my_comment' => [[1, 2, 3]], 
            'edit_other_comments' => [[1, 2]], 
            'remove_my_comment' => [[1, 2, 3]],
            'remove_other_comments' => [[1, 2]], 
            
            'manage_menus' => [[1]], 
            
            'view_files' => [[1, 2, 3]], 
            'publish_files' => [[1, 2, 3]], 
            'edit_my_file' => [[1, 2, 3]],  
            'edit_other_files' => [[1, 2]],
            'remove_my_file' => [[1, 2, 3]],
            'remove_other_files' => [[1, 2]], 
            
            'manage_options' => [[1]]
        ];
        
        foreach ($caps as $cap => $attrs) {
            $data = ['name' => $cap, 'label' => $cap];
            $cap_item = Cap::create($data);
            $cap_item->roles()->attach($attrs[0]);
        }
        
        $this->insertSeeder();
    }
}
