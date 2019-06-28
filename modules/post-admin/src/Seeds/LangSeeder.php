<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\Models\Lang;

class LangSeeder extends BaseSeeder
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
        
        Lang::create([
            'name' => 'Tiếng Việt', 
            'code' => 'vi', 
            'icon' => 'vi.png', 
            'folder' => 'vi', 
            'unit' => 'Đ', 
            'ratio_currency' => 1, 
            'order' => 1, 
            'default' => 1
        ]);
        
        Lang::create([
            'name' => 'English', 
            'code' => 'en', 
            'icon' => 'en.png', 
            'folder' => 'en', 
            'unit' => '$', 
            'ratio_currency' => '23000', 
            'order' => 2, 
            'default' => 0
        ]);
        
        $this->insertSeeder();
    }
}
