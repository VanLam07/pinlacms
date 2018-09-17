<?php

namespace Dict\Seeds;

use Admin\Seeds\BaseSeeder;
use Dict\Models\DictEnVn;
use Illuminate\Support\Facades\DB;

class DictOriginSeeder extends BaseSeeder
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
        
        DB::beginTransaction();
        try {
            $collection = DictEnVn::where('origin', 'like', '%<br />%')->get();
            if (!$collection->isEmpty()) {
                foreach ($collection as $dict) {
                    $arrOrigin = explode('<br />', $dict->origin);
                    $dict->origin = $arrOrigin[0];
                    $dict->save();
                }
            }
            $this->insertSeeder();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
        }
    }
}
