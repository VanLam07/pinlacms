<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $fillable = [
        'type', 'name', 'slug', 'email', 'password', 'birth', 'gender', 'status', 'image_id', 'image_url', 'role_id'
    ];

    protected $dates = ['created_at', 'updated_at', 'birth'];
    
    public function roles() {
        return $this->belongsToMany('\App\Models\Role', 'user_role', 'user_id', 'role_id');
    }
    
    public function caps(){
        $roles = $this->roles;
        $caps = [];
        if (!$roles->isEmpty()) {
            foreach ($roles as $role) {
                $caps = array_unique(array_merge($caps, $role->caps->pluck('name')->toArray()));
            }
        }
        return $caps;
    }
    
    public function hasCaps($caps) {
        if (!is_array($caps)) {
            $caps = [$caps];
        }
        $userCaps = $this->caps();
        if (!$userCaps) {
            return false;
        }
        return !array_diff($caps, $userCaps);
    }
    
    public function avatar() {
        return $this->belongsTo('\App\Models\File', 'image_id', 'id');
    }
    
    public function getAvatarSrc($size = 'thumbnail') {
        if ($this->avatar) {
            return $this->avatar->getSrc($size);
        }
        if ($this->image_url) {
            return $this->image_url;
        }
        return '/images/icon/user-icon.png';
    } 
    
    public function getAvatar($size='thumbnail', $class='') {
        if ($this->avatar) {
            return $this->avatar->getImage($size, $class);
        }
        if ($this->image_url) {
            return '<img class="img-fluid" src="'.$this->image_url.'" alt=" ">';
        }
        return '<img  class="img-fluid" src="/images/icon/user-icon.png" alt=" ">';
    }
    
    public function status(){
        switch ($this->status){
            case self::STT_TRASH:
                return trans('manage.trash');
            case self::STT_BANNED:
                return trans('manage.banned');
            case self::STT_ACTIVE:
                return trans('manage.active');
            default:
                return trans('amange.disable');
        }
    }
    
    public static function arrStatus() {
        return [
            self::STT_ACTIVE => trans('manage.active'),
            self::STT_DISABLE => trans('manage.disable'),
            self::STT_BANNED => trans('manage.ban'),
            self::STT_TRASH => trans('manage.trash')
        ];
    }

    public function rules($id = null) {
        if (!$id) {
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ];
        }
        return [
            'name' => 'required',
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'min:6'
        ];
    }

    public function getData($args = []) {
        $opts = [
            'status' => 1,
            'field' => ['*'],
            'orderby' => 'id',
            'order' => 'asc',
            'per_page' => 20,
            'exclude' => [],
            'key' => '',
            'withs' => ['roles'],
            'page' => 1
        ];

        $opts = array_merge($opts, $args);

        $result = self::where('status', $opts['status'])
                        ->whereNotIn('id', $opts['exclude'])
                        ->where('email', 'like', '%' . $opts['key'] . '%')
                        ->orderby($opts['orderby'], $opts['order']);
        if ($opts['withs']) {
            $result = $result->with($opts['withs']);
        }
        
        $result = $result->paginate($opts['per_page']);
        
        return $result;
    }

    public function getRoles() {
        $roles = $this->roles;
        if ($roles->isEmpty()) {
            return null;
        }
        $result = '';
        foreach ($roles as $role) {
            $result .= $role->label . ', ';
        }
        return trim($result, ', ');
    }
    
    public function changeStatus($ids, $status) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        self::whereIn('id', $ids)
                ->update(['status' => $status]);
    }
}
