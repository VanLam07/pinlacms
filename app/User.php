<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Admin\Facades\AdConst;
use App\Exceptions\PlException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Validator;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $fillable = [
        'type', 'name', 'slug', 'email', 'password', 'birth', 'gender', 'status', 'image_id', 'image_url', 'role_id', 'remember_token'
    ];

    protected $dates = ['birth'];
    
    
    public static function isUseSoftDelete() {
        return true;
    }
    
    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function getSessionKey() {
        return 'user_cap_'.$this->name.'_'.sha1($this->id);
    }
    
    public static function rules($id = null) {
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
    
    public function roles() {
        return $this->belongsToMany('\App\Models\Role', 'user_role', 'user_id', 'role_id');
    }
    
    public function caps(){
        $userKey = $this->getSessionKey();
        
        if (Session::has($userKey) && $caps = Session::get($userKey)) {
            return unserialize($caps);
        }
        
        $roles = $this->roles;
        $caps = [];
        if ($roles->isEmpty()) {
            return $caps;
        }
        foreach ($roles as $role) {
            $listCaps = $role->getListCaps();
            if (!$listCaps) {
                continue;
            }
            foreach ($listCaps as $key => $level) {
                if (isset($caps[$key])) {
                    if ($caps[$key] < $level) {
                        $caps[$key] = $level;
                    }
                } else {
                    $caps[$key] = $level;
                }
            }
        }
        Session::put($userKey, serialize($caps));
        
        return $caps;
    }
    
    public function storeCapsToSession() {
        $caps = $this->caps();
        if ($caps) {
            Session::put($this->getSessionKey(), serialize($caps));
        }
    }
    
    public function hasCaps($caps) {
        if (!is_array($caps)) {
            $caps = [$caps];
        }
        $userCaps = $this->caps();
        if (!$userCaps) {
            return false;
        }
        foreach ($caps as $cap) {
            if (array_key_exists($cap, $userCaps)) {
                return true;
            }
        }
        return false;
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
    
    public function getAvatar($size = 32, $class='') {
        if ($this->avatar) {
            return $this->avatar->getImage('thumbnail', $class);
        }
        if ($this->image_url) {
            return '<img class="img-responsive" width="'. $size .'" src="'.$this->image_url.'" alt=" ">';
        }
        return '<img  class="img-responsive" width="'. $size .'" src="/images/icon/user-icon.png" alt=" ">';
    }
    
    public static function getData($data) {
        $opts = [
            'fields' => ['*'],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => AdConst::PER_PAGE,
            'status' => [AdConst::STT_PUBLISH],
            'exclude_key' => 'id',
            'exclude' => [],
            'page' => 1,
            'filters' => [],
        ];
        
        $opts = array_merge($opts, $data);
        
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
        $result->orderby($opts['orderby'], $opts['order']);
        
        if($opts['per_page'] == -1){
            return $result->get();
        }
        return $result->paginate($opts['per_page']);
    }
    
    public function filterData(&$collection, $filters) {
        if ($filters && is_array($filters)) {
            foreach ($filters as $key => $value) {
                if (is_array($value)) {
                    $collection->whereIn($key, $value);
                } else {
                    if ($value) {
                        $collection->where($key, 'like', "%$value%");
                    }
                }
            }
        }
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
    
    public static function changeStatus($ids, $status) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)->update(['status' => $status]);
    }
    
    public static function forceDeleteData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $items = self::withTrashed()
                ->whereIn('id', $ids)->get();
        if (!$items->isEmpty()) {
            foreach ($items as $item) {
                $item->forceDelete();
            }
        }
    }
    
    public static function destroyData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::destroy($ids);
    }
    
    public static function restoreData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)
                ->restore();
    }

    public static function actions($request) {
        $valid = Validator::make($request->all(), [
            'action' => 'required',
            'item_ids.*' => 'required' 
        ]);
        if ($valid->fails()) {
            throw new ValidationException($valid, 422);
        }

        $item_ids = $request->input('item_ids');
        if (!$item_ids) {
            throw new Exception(trans('admin::message.no_item'));
        }
        $action = $request->input('action');
        switch ($action) {
            case 'restore':
                self::restoreData($item_ids);
                break;
            case 'publish':
                self::changeStatus($item_ids, AdConst::STT_PUBLISH);
                break;
            case 'draft': 
                self::changeStatus($item_ids, AdConst::STT_DRAFT);
                break;
            case 'trash':
                self::destroyData($item_ids);
                break;
                break;
            case 'delete':
                self::forceDeleteData($item_ids);
                break;
            case defalt:
                break;
        }
    }
    
    public function authorId() {
        return $this->id;
    }
    
//    public function setSlugAttribute($value)
//    {
//        $exists = self::where('slug', 'like', $value . '%')
//                ->get();
//        $count = $exists->count();
//        if ($count == 0 || ($count == 1 && $this->id == $exists->first()->id)) {
//            $slug = $value;
//        } else {
//            $slug = $value . '_' . ($exists->count() + 1);
//        }
//        $this->attributes['slug'] = $slug;
//    }
}
