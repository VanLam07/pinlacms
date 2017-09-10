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
            'fields' => ['*'],
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'exclude' => [],
            'key' => '',
            'page' => 1
        ];
        
        $opts = array_merge($opts, $args);
        
        $result = self::whereNotIn('name', $opts['exclude'])
                ->search($opts['key'])
                ->orderby($opts['orderby'], $opts['order'])
                ->select($opts['fields']);
        
        if($opts['per_page'] == -1){
            $result = $result->get();
        }else{
            $result = $result->paginate($opts['per_page']);
        }
        return $result;
    }
    
    public function scopeSearch($query, $key){
        return $query->where('name', 'like', "%$key%");
    }
    
    public function roles() {
        return $this->belongsToMany('\App\Models\Role', 'role_cap', 'cap_name', 'role_id');
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
