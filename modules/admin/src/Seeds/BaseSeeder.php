<?php

namespace Admin\Seeds;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder {
    
    protected $tbl = 'migrations';

    public function checkSeeder($key = null) {
        if (!$key) {
            $key = get_class($this);
        }
        $hasSeeder = DB::table($this->tbl)
                ->where('migration', $key)
                ->first();
        if (!$hasSeeder) {
            return false;
        }
        return true;
    }
    
    public function insertSeeder($key = null) {
        if (!$key) {
            $key = get_class($this);
        }
        DB::table($this->tbl)
                ->insert(['migration' => $key, 'batch' => 1]);
    }
    
}

