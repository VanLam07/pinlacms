<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Admin\Facades\AdConst;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Exception;

class User extends Authenticatable
{
    use Notifiable;

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $fillable = [
        'type', 'name', 'slug', 'email', 'password', 'birth', 'gender', 'status', 'image_id', 'image_url', 'role_id', 'remember_token'
    ];

    protected $dates = ['birth'];
    
    use SoftDeletes;
    
    public function isUseSoftDelete() {
        return true;
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
    
    public function roles() {
        return $this->belongsToMany('\App\Models\Role', 'user_role', 'user_id', 'role_id');
    }
    
    public function caps(){
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
            case AdConst::STT_TRASH:
                return trans('admin::view.trash');
            case AdConst::STT_PUBLISH:
                return trans('admin::view.publish');
            default:
                return trans('amange.draft');
        }
    }
    
    public function getData($data) {
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
            $this->filterData($result, $opts['filters']);
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
    
    public function changeStatus($ids, $status) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)->update(['status' => $status]);
    }
    
    public function forceDeleteData($ids) {
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
    
    public function destroyData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::destroy($ids);
    }
    
    public function restoreData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)
                ->restore();
    }

    public function actions($request) {
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
                $this->restoreData($item_ids);
                break;
            case 'publish':
                $this->changeStatus($item_ids, AdConst::STT_PUBLISH);
                break;
            case 'draft': 
                $this->changeStatus($item_ids, AdConst::STT_DRAFT);
                break;
            case 'trash':
                $this->destroyData($item_ids);
                break;
                break;
            case 'delete':
                $this->forceDeleteData($item_ids);
                break;
            case defalt:
                break;
        }
    }
}
