<?php

namespace Dict\Models;

use App\Models\BaseModel;
use App\User;
use PlOption;

class DictSentence extends BaseModel
{
    protected $table = 'dict_sentences';
    protected $fillable = ['word_id', 'user_id', 'sentence'];
    
    public function author()
    {
        return $this->belongsTo('\App\User', 'user_id', 'id');
    }
    
    public static function getData($data) {
        $perPage = PlOption::get('per_page');
        $opts = [
            'word_id' => null,
            'with_author' => false,
            'fields' => ['st.*', 'user.name as user_name'],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => $perPage ? $perPage : AdConst::PER_PAGE,
            'status' => [],
            'exclude_key' => 'id',
            'exclude' => [],
            'page' => 1,
            'filters' => [],
        ];
        
        $opts = array_merge($opts, $data);
        
        $result = self::select($opts['fields'])
                ->from(self::getTableName() . ' as st')
                ->leftJoin(User::getTableName() . ' as user', 'st.user_id', '=', 'user.id')
                ->groupBy('st.id');
        
        if ($opts['with_author']) {
            $result->with('author');
        }
        if ($opts['word_id']) {
            $result->where('word_id', $opts['word_id']);
        }
        if ($opts['exclude']) {
            $result->whereNotIn($opts['exclude_key'], $opts['exclude']);
        }
        if ($opts['filters']) {
            self::filterData($result, $opts['filters']);
        }
        $result->orderby($opts['orderby'], $opts['order']);
        
        if($opts['per_page'] == -1){
            return $result->get();
        }
        return $result->paginate($opts['per_page']);
    }
}
