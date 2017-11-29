<?php

namespace App\Models;

class Cap extends BaseModel
{
    protected $table = 'caps';
    protected $fillable = ['label', 'name'];
    public $timestamps = false;
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $capCreate = 'manage_cats';
    protected $capEdit = 'manage_cats';
    protected $capRemove = 'manage_cats';

    public static function rules(){
        return [
            'name' => 'required|alpha_dash|unique:caps,name'
        ];
    }

    public static function getData($args = []) {
        $opts = [
            'orderby' => 'name',
            'order' => 'asc',
        ];
        $opts = array_merge($opts, $args);
        return parent::getData($opts);
    }
    
    public function roles() {
        return $this->belongsToMany('\App\Models\Role', 'role_cap', 'cap_name', 'role_id')
                ->withPivot('level');
    }
    
    public static function updateData($name, $data) {
        $item = self::findOrFail($name);
        $fillable = $item->getFillable();
        $data = array_only($data, $fillable);
        return $item->update($data);
    }
    
    public static function findByName($name) {
        return self::where('name', $name)->first();
    }
}
