<?php

namespace App\Models;

use Admin\Facades\AdConst;

class Contact extends BaseModel {

    protected $table = 'contact';
    protected $fillable = ['fullname', 'email', 'subject', 'content', 'ip', 'read_at', 'status'];
    protected $capCreate = 'publish_contact';
    protected $capEdit = 'edit_contact';
    protected $capRemove = 'remove_contact';
    
    public static function rules()
    {
        return [
            'fullname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|max:255',
            'content' => 'required|max:500'
        ];
    }

    public static function getData($args = []) {
        $opts = [
            'fields' => ['*'],
            'status' => [AdConst::STT_PUBLISH],
            'orderby' => 'created_at',
            'order' => 'asc',
            'per_page' => AdConst::PER_PAGE,
            'exclude_key' => 'id',
            'exclude' => [],
            'filters' => [],
            'email' => null
        ];
        $opts = array_merge($opts, $args);
        
        $result = self::select($opts['fields']);
        
        if ($opts['status']) {
            if (!is_array($opts['status'])) {
                $opts['status'] = [$opts['status']];
            }
            if ($opts['status'][0] == AdConst::STT_TRASH) {
                $result->onlyTrashed();
            } else {
                $result->whereIn('status', $opts['status']);
            }
        }
        
        if ($opts['exclude']) {
            $result->whereNotIn($opts['exclude_key'], $opts['exclude']);
        }
        
        if ($opts['filters']) {
            self::filterData($result, $opts['filters']);
        }
        
        $result->orderBy($opts['orderby'], $opts['order']);

        if ($opts['per_page'] > -1) {
            return $result->paginate($opts['per_page']);
        }
        return $result->get();
    }

    public static function insertData($data) {
        self::validator($data, self::rules());
        
        return self::create($data);
    }

    public static function updateData($id, $data) {
        self::validator($data, self::rules());

        $item = self::findOrFail($id);
        $fillable = $item->getFillable();
        $data = array_only($data, $fillable);
        return $item->update($data);
    }
}
