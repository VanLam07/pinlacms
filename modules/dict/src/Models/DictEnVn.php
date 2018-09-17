<?php

namespace Dict\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Session;

class DictEnVn extends BaseModel {

    protected $table = 'dict_en_vn';
    protected $fillable = ['word', 'type', 'origin', 'pronun', 'mean', 'detail', 'detail_origin'];
    public $timestamps = false;
    
    const KEY_TOTAL = 'dict_en_vn_total';
    
    public static function isUseSoftDelete()
    {
        return false;
    }

    public static function transType($type)
    {
        $type = explode(',', $type)[0];
        switch($type) {
            case 'danh từ':
                return 'noun';
            case 'tính từ':
                return 'adjective';
            case 'trạng từ':
            case 'phó từ':
                return 'adverb';
            case 'động từ':
                return 'verb';
            case 'ngoại động từ':
                return 'transitive verb';
            case 'nội động từ':
                return 'intransitive verb';
            case 'đại từ':
                return 'pronoun';
            case 'giới từ':
                return 'preposition';
            case 'liên từ':
                return 'conjunction';
            case 'thán từ':
                return 'interjection';
            default:
                return false;
        }
    }
    
    public static function listTypes()
    {
        return [
            'v.',
            'n.',
            'adj.',
            'adv.',
            'det.',
            'pron.'
        ];
    }
    
    public static function getData($args = []) {
        $opts = [
            'orderby' => 'word',
            'order' => 'asc',
            'per_page' => 30
        ];
        $opts = array_merge($opts, $args);
        return parent::getData($opts);
    }
    
    public static function rules($id = null)
    {
        return [
            'word' => 'required',
            'origin' => 'required'
        ];
    }
    
    public static function insertData($data) {
        parent::insertData($data);
        Cache::forget(self::KEY_TOTAL);
    }
    
    public static function updateData($id, $data) {
        parent::updateData($id, $data);
        Cache::forget(self::KEY_TOTAL);
    }
    
    public static function getTotal()
    {
        if ($total = Cache::get(self::KEY_TOTAL)) {
            return $total;
        }
        $total = self::count('id');
        Cache::put(self::KEY_TOTAL, $total);
        return $total;
    }
    
    public static function getRandWord()
    {
        return self::select(
                'id', 'word', 'type', 'pronun', 'mean',
                DB::raw('IFNULL(detail, detail_origin) as detail')
            )
            ->orderBy(DB::raw('RAND()'))
            ->first();
    }
    
    public static function saveTempWord($word, $sessionId = null)
    {
        if (!$sessionId) {
            $sessionId = Session::getId();
        }
        Cache::put('word_' . $sessionId, $word, 24 * 60);
    }
    
    public static function makeRandWord($withSession = false)
    {
        $sessionId = Session::getId();
        if ($withSession) {
            if ($word = Cache::get('word_' . $sessionId)) {
                return $word;
            }
            $word = self::getRandWord();
            self::saveTempWord($word, $sessionId);
            return $word;
        }
        $word = self::getRandWord();
        self::saveTempWord($word, $sessionId);
        return $word;
    }

}
