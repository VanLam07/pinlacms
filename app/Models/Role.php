<?php

namespace App\Models;

class Role extends BaseModel {

    protected $table = 'roles';
    protected $fillable = ['label', 'name', 'default'];
    public $timestamps = false;

    public function rules($id = null) {
        $id = ($id) ? ',' . $id : '';
        return [
            'name' => 'required|alpha_dash|unique:roles,name' . $id
        ];
    }

    public function getData($args = []) {
        $opts = [
            'field' => ['*'],
            'orderby' => 'id',
            'order' => 'asc',
            'per_page' => 20,
            'key' => '',
            'page' => 1
        ];

        $opts = array_merge($opts, $args);

        return self::where('name', 'like', '%' . $opts['key'] . '%')
                        ->orderby($opts['orderby'], $opts['order'])
                        ->paginate($opts['per_page']);
    }

    public function getDefaultId() {
        $item = self::where('default', 1)->select('id')->first();
        if ($item) {
            return $item->id;
        }
        return 0;
    }

    public function updateData($id, $data) {
        $this->validator($data, $this->rules($id));

        $item = self::find($id);
        if (isset($data['caps']) && $data['caps']) {
            $item->caps()->sync($data['caps']);
        }
        
        $fillable = self::getFillable();
        $data = array_only($data, $fillable);
        return self::where('id', $id)->update($data);
    }

    public function caps() {
        return $this->belongsToMany('\App\Models\Cap', 'role_cap', 'role_id', 'cap_name')
                ->withPivot('level');
    }
    
    public function updateListCaps() {
        $roleCaps = [];
        $caps = $this->caps;
        if ($caps->isEmpty()) {
            return;
        }
        foreach ($caps as $cap) {
            if (isset($roleCaps[$cap->name])) {
                if ($roleCaps[$cap->name] < $cap->pivot->level) {
                    $roleCaps[$cap->name] = $cap->pivot->level;
                }
            } else {
                $roleCaps[$cap->name] = $cap->pivot->level;
            }
        }
        if (!$roleCaps) {
            return;
        }
        $this->list_caps = serialize($roleCaps);
        $this->save();
    }
    
    public function getListCaps() {
        if (!$this->list_caps) {
            return [];
        }
        return unserialize($this->list_caps);
    }

    public function str_default() {
        if ($this->default == 0) {
            return trans('manage.no');
        }
        return trans('manage.yes');
    }

}
