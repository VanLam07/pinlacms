<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\Models\Option;

class OptionSeeder extends BaseSeeder
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
        $data = [
            ['option_key' => 'blog_description', 'value' => 'Blog description'],
            ['option_key' => 'blog_keywords', 'value' => 'blog'],
            ['option_key' => 'blog_title', 'value' => 'Blog'],
            ['option_key' => 'cms_title', 'value' => 'Admin blog'],
            ['option_key' => 'google_analytics', 'value' => 'script'],
            ['option_key' => 'google_webmaster_meta', 'value' => 'key'],
        ];
        foreach ($data as $item) {
            if (Option::where('option_key', $item['option_key'])->first()) {
                continue;
            }
            Option::created($item);
        }
        $this->insertSeeder();
    }
}
