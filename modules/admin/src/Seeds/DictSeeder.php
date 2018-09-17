<?php

namespace Admin\Seeds;

use Admin\Seeds\BaseSeeder;
use App\Models\DictEnVn;
use App\Models\DictDraft;
use Illuminate\Support\Facades\DB;

class DictSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($this->checkSeeder(1)) {
            return;
        }
        
        DB::beginTransaction();
        try {
            DictDraft::chunk(500, function ($dicts) {
                $dataWords = [];
                foreach ($dicts as $dict) {
                    $dictWord = preg_replace('/\((.*)\)/', '', $dict->word);
                    $detail = preg_replace('(<C><F><I><N><Q>)', '', $dict->detail);
                    $detail = preg_replace('(<\/Q><\/N><\/I><\/F><\/C>)', '', $detail);
                    $arrWords = explode('<br />*', $detail);
                    $arrWords = array_map(function ($str) {
                        return trim($str);
                    }, $arrWords);

                    if (count($arrWords) == 1) {
                        $originWord = preg_replace('/(\s=\s)|(<br \/>\?\?\?)/', '<br />- ', $arrWords[0]);
                        if (count(explode('<br />- ', $originWord)) == 1) {
                            $originWord .= '<br />- ';
                        }
                        preg_match('/@(.*)<br\s\/>-\s(.*)/', $originWord, $originMean);
                        if (!isset($originMean[1])) {
                            dump($dict, $originWord, $originMean);
                        }
                        $dataWords[] = [
                            'word' => $dictWord,
                            'type' => null,
                            'pronun' => null,
                            'origin' => $originMean[1],
                            'mean' => $originMean[2],
                            'detail' => null,
                            'detail_origin' => $dict->detail
                        ];
                    } else {
                        $pronunOrigin = array_shift($arrWords);
                        $pronunOrigin = preg_replace('/<br \/>(.*)/', '', $pronunOrigin);
                        $pronunOrigin = preg_replace('/\/\((.*)\)/', '/', $pronunOrigin);
                        $pronunOrigin = preg_replace('/\/\s\((.*)\)/', '/', $pronunOrigin);
                        if (preg_match('/\/\s/', $pronunOrigin)) {
                            $pronunOrigin = preg_replace('/\/\s(.*)/', '/$1/', $pronunOrigin);
                        }
                        if (preg_match('/\s\//', $pronunOrigin)) {
                            preg_match('/\@(.*)\s\/(.*)\//', $pronunOrigin, $matchPronun);
                            if (!isset($matchPronun[1])) {
                                dump($dict, $pronunOrigin, $matchPronun);
                            }
                            $origin = $matchPronun[1];
                            $pronun = '/'. $matchPronun[2] . '/';
                        } else {
                            preg_match('/\@(.*)/', $pronunOrigin, $matchOrigin);
                            $origin = $matchOrigin[1];
                            $pronun = null;
                        }
                        $dataWord = [
                            'origin' => $origin,
                            'pronun' => $pronun,
                            'detail_origin' => $dict->detail
                        ];
                        foreach ($arrWords as $word) {
                            $arrMeans = explode('<br />', $word);
                            $typeOrigin = array_shift($arrMeans);
                            $wordType = DictEnVn::transType($typeOrigin);
                            $dataWord['word'] = $dictWord;
                            $dataWord['type'] = $wordType ? $wordType : null;
                            $dataWord['mean'] = $wordType ? array_shift($arrMeans) : $typeOrigin;
                            $dataWord['detail'] = implode('<br />', $arrMeans);
                            $dataWords[] = $dataWord;
                        }
                    }
                }
                DictEnVn::insert($dataWords);
            });
            $this->insertSeeder();
            DB::commit();
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();
        }
    }
}
