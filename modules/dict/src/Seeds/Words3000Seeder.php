<?php

namespace Dict\Seeds;

use Admin\Seeds\BaseSeeder;
use Dict\Models\DictEnVn;
use Illuminate\Support\Facades\DB;
use Storage;

class Words3000Seeder extends BaseSeeder
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
        $prefixPath = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
        $filePath = $prefixPath . 'dicts.txt';
        
        DB::beginTransaction();
        try {
            $file = fopen($filePath, "r") or die('File error!');
            $dataInsert = [];
            while (!feof($file)) {
                $line = trim(fgets($file));
                if (!$line) {
                    continue;
                }
                preg_match('/(.*)\/(.*)\/(.*)/', $line, $matchs);
                $type = null;
                $mean = null;
                $pronun = null;
                if (isset($matchs[2])) {
                    $pronun = '/'.$matchs[2].'/';
                    $mean = trim($matchs[3]);
                    $words = explode(' ', trim($matchs[1]));
                    $i = 0;
                    while (isset($words[$i]) && !preg_match('/(.+)\./', $words[$i])) {
                        $i++;
                    }
                    if ($i > 0) {
                        $word = implode(' ', array_splice($words, 0, $i));
                        $type = implode(' ', $words);
                    } else {
                        $word = $words[$i];
                    }
                } else {
                    //word mean
                    if (preg_match('/(.+)\./', $line)) {
                        $words = explode(' ', $line);
                        $i = 0;
                        while (isset($words[$i]) && !preg_match('/(.+)\./', $words[$i])) {
                            $i++;
                        }
                        if ($i > 0) {
                            $word = implode(' ', array_splice($words, 0, $i));
                            $arrTypes = explode('. ', implode(' ', $words));
                            if (count($arrTypes) > 1) {
                                $type = $arrTypes[0] . '.';
                                $mean = $arrTypes[1];
                            } else {
                                $mean = $arrTypes[0];
                            }
                        } else {
                            $words = explode('  ', implode(' ', $words));
                            $word = $words[0];
                            if (isset($words[1])) {
                                $mean = $words[1];
                            }
                        }
                    } else {
                        $words = explode('  ', $line);
                        $word = $words[0];
                        if (isset($words[1])) {
                            $mean = $words[1];
                        }
                    }
                }
                $dataInsert[] = [
                    'word' => $word,
                    'pronun' => $pronun,
                    'origin' => $word,
                    'type' => $type,
                    'mean' => $mean,
                    'detail_origin' => $line
                ];
            }
            fclose($file);
            DictEnVn::insert($dataInsert);
            
            $this->insertSeeder();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex);
        }
    }
}
