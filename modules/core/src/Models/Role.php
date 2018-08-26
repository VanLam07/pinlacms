<?php

namespace App\Models;

use Admin\Facades\AdConst;

class Role extends BaseModel {

    protected $table = 'roles';
    protected $fillable = ['label', 'name', 'default', 'list_caps'];
    public $timestamps = false;
    
    public static function isUseSoftDelete() {
        return false;
    }

    public static function rules($id = null) {
        $id = ($id) ? ',' . $id : '';
        return [
            'name' => 'required|alpha_dash|unique:roles,name' . $id
        ];
    }

    public static function getData($args = []) {
        $opts = [
            'orderby' => 'name',
            'order' => 'asc',
        ];
        return parent::getData(array_merge($opts, $args));
    }

    public static function getDefaultId() {
        $item = self::where('default', 1)->select('id')->first();
        if ($item) {
            return $item->id;
        }
        return 0;
    }

    public static function updateData($id, $data) {
        self::validator($data, self::rules($id));

        $item = self::findOrFail($id);
        if (isset($data['caps_level']) && $data['caps_level']) {
            $dataCaps = [];
            foreach ($data['caps_level'] as $capName => $level) {
                if (is_numeric($level)) {
                    $dataCaps[$capName] = ['level' => $level];
                }
            }
            $item->caps()->sync($dataCaps);
        } else {
            $item->caps()->sync([]);
        }
        $item->updateListCaps();
        
        $fillable = $item->getFillable();
        $data = array_only($data, $fillable);
        return self::where('id', $id)->update($data);
    }

    public function caps() {
        return $this->belongsToMany('\App\Models\Cap', 'role_cap', 'role_id', 'cap_name')
                ->withPivot('level');
    }
    
    public function updateListCaps($save = true) {
        $roleCaps = [];
        $caps = $this->caps;
        if (!$caps->isEmpty()) {
            foreach ($caps as $cap) {
                if (isset($roleCaps[$cap->name])) {
                    if ($roleCaps[$cap->name] < $cap->pivot->level) {
                        $roleCaps[$cap->name] = $cap->pivot->level;
                    }
                } else {
                    $roleCaps[$cap->name] = $cap->pivot->level;
                }
            }
        }
        AdConst::forgetAllUserCaps();
        if ($roleCaps) {
            $this->list_caps = serialize($roleCaps);
        } else {
            $this->list_caps = null;
        }
        if ($save) {
            $this->save();
        }
        return $this;
    }
    
    public function getListCaps() {
        if (!$this->list_caps) {
            return [];
        }
        return unserialize($this->list_caps);
    }
    
    public function listCapsLevel() {
        $caps = $this->caps;
        if ($caps->isEmpty()) {
            return [];
        }
        $list = [];
        foreach ($caps as $cap) {
            $list[$cap->name] = $cap->pivot->level;
        }
        return $list;
    }

    public function strDefault() {
        if ($this->default == 0) {
            return trans('admin::view.no');
        }
        return trans('admin::view.yes');
    }

}
