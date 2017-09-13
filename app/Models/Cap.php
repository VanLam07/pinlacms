<?php

namespace App\Models;

class Cap extends BaseModel
{
    protected $table = 'caps';
    protected $fillable = ['label', 'name'];
    public $timestamps = false;
    protected $primaryKey = 'name';
    public $incrementing = false;
    
    public function rules(){
        return [
            'name' => 'required|alpha_dash'
        ];
    }

    public function getData($args = []) {
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
    
    public function updateData($id, $data) {
        $this->validator($data, $this->rules());
        
        $fillable = self::getFillable();
        $data = array_only($data, $fillable);
        return self::where('name', $id)->update($data);
    }
    
    function findByName($name) {
        return self::where('name', $name)->first();
    }
}
