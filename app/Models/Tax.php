<?php

namespace App\Models;

use App\Models\BaseModel;
use Admin\Facades\AdConst;

class Tax extends BaseModel
{
    protected $table = 'taxs';
    protected static $tblDesc = 'tax_desc';
    protected $fillable = ['image_id', 'type', 'parent_id', 'order', 'count', 'status'];

    public static function joinLang($code = null) {
        if (!$code) {
            $code = currentLocale();
        }
        return self::from(self::getTableName() . ' as taxs')
                ->join(self::$tblDesc . ' as td', 'taxs.id', '=', 'td.tax_id')
                ->where('td.lang_code', '=', $code);
    }
    
    public static function rules($update = false) {
        if ($update) {
            return [
                'locale.name' => 'required',
                'lang' => 'required'
            ];
        }
        $code = currentLocale();
        return [
            $code . '.name' => 'required'
        ];
    }
    
    public function getName($lang=null){
        $item = self::joinLang($lang)
                ->find($this->id, ['td.name']);
        if($item){
            return $item->name;
        }
        return null;
    }
    
    public function parentName() {
        $item = self::joinLang()
                ->where('taxs.id', $this->parent_id)
                ->first(['td.name']);

        if ($item) {
            return $item->name;
        }
        return null;
    }
    
    public function isTag()
    {
        return $this->type == 'tag';
    }
    
    public function isCategory()
    {
        return $this->type == 'cat';
    }

    public function langs(){
        return $this->belongsToMany('\App\Models\Lang', 'tax_desc', 'tax_id', 'lang_code');
    }
    
    public function thumbnail() {
        return $this->belongsTo('\App\Models\File', 'image_id', 'id');
    }
    
    public function getThumbnailSrc($size) {
        if ($this->thumbnail) {
            return $this->thumbnail->getSrc($size);
        }
        return null;
    }
    
    public function getThumbnail($size='thumbnail', $class = null){
        if ($this->thumbnail) {
            return $this->thumbnail->getImage($size, $class);
        }
        return null;
    }
    
    public function menuItems()
    {
        return $this->hasMany('\App\Models\Menu', 'group_id');
    }

    public static function getData($type = 'cat', $args = []) {
        $opts = [
            'fields' => ['taxs.*', 'td.*'],
            'orderby' => 'td.name',
            'order' => 'asc',
            'status' => [AdConst::STT_PUBLISH],
            'per_page' => AdConst::PER_PAGE,
            'exclude_key' => 'taxs.id',
            'exclude' => [],
            'page' => 1,
            'filters' => []
        ];
        
        $opts = array_merge($opts, $args);

        $result = self::joinLang()
                ->where('type', $type)
                ->whereNotNull('td.name');
        if ($opts['exclude']) {
            $result->whereNotIn($opts['exclude_key'], $opts['exclude']);
        }
        if ($opts['status']) {
            if (!is_array($opts['status'])) {
                $opts['status'] = [$opts['status']];
            }
            $result->whereIn('status', $opts['status']);
        }
        $result->select($opts['fields'])
                ->orderBy($opts['orderby'], $opts['order']);
        
        if ($opts['filters']) {
            self::filterData($result, $opts['filters']);
        }

        if ($opts['per_page'] > -1) {
            return $result->paginate($opts['per_page']);
        }
        
        return $result->get();
    }

    public static function insertData($data, $type = 'cat') {
        self::validator($data, self::rules());

        if(isset($data['parent_id']) && $data['parent_id'] == 0){
            $data['parent_id'] = null;
        }
        $data['type'] = $type;
        if(isset($data['file_ids']) && $data['file_ids']){
            $data['image_id'] = $data['file_ids'][0];
        }
        if (!isset($data['order']) || !$data['order']) {
            $data['order'] = 0;
        }
        $item = self::create($data);

        $allLangs = getLangs();
        foreach ($allLangs as $lang) {
            $langData = $data[$lang->code];
            $name = $langData['name'];
            $slug = isset($langData['slug']) ? $langData['slug'] : '';
            $langData['slug'] = (trim($slug) == '') ? str_slug($name) : str_slug($slug);

            $item->langs()->attach($lang->code, $langData);
        }
        return $item;
    }

    public static function findByLang($id, $fields = ['taxs.*', 'td.*'], $lang = null) {
        $item = self::joinLang($lang)
                ->find($id, $fields);
        if($item){
            return $item;
        }
        return self::findOrFail($id);
    }

    public static function updateData($id, $data) {
        self::validator($data, self::rules(true));

        if(isset($data['file_ids']) && $data['file_ids']){
            $data['image_id'] = $data['file_ids'][0];
        }
        if(isset($data['parent_id']) && $data['parent_id'] == 0){
            $data['parent_id'] = null;
        }
        if (!isset($data['order']) || !$data['order']) {
            $data['order'] = 0;
        }
        
        $item = self::findOrFail($id);
        $fillable = $item->getFillable();
        $fillData = array_only($data, $fillable);
        $item->update($fillData);

        $langData = $data['locale'];
        $name = $langData['name'];
        $slug = isset($langData['slug']) ? $langData['slug'] : '';
        $langData['slug'] = (trim($slug) == '') ? str_slug($name) : str_slug($slug);

        $item->langs()->sync([$data['lang'] => $langData], false);
    }
    
    public static function tableCats($items, $parent = 0, $depth = 0) {
        $html = '';
        $indent = str_repeat("-- ", $depth);
        foreach ($items as $item) {
            if ($item->parent_id == $parent && $item->name) {
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" name="check_items[]" class="check_item" value="' . $item->id . '" /></td>
                <td>' . $item->id . '</td>
                <td>' . $indent . ' ' . $item->name . '</td>
                <td>' . $item->slug . '</td>
                <td>' . $item->parentName() . '</td>
                <td>' . $item->order . '</td>
                <td><a href="'.route('admin::post.index', ['cats' => [$item->id], 'status' => AdConst::STT_PUBLISH]).'">' . $item->count . '</a></td>
                <td>
                    <a href="' . route('admin::cat.edit', ['id' => $item->id]) . '" '
                        . 'class="btn btn-sm btn-info" title="' . trans('admin::view.edit') . '">
                            <i class="fa fa-edit"></i>
                    </a>
                </td>';
                $html .= '</tr>';
                $html .= self::tableCats($items, $item->id, $depth + 1);
            }
        }
        return $html;
    }
    
    public static function toNested($items, $parent = 0) {
        $results = [];
        foreach ($items as $item) {
            if ($item->parent_id == $parent) {
                $nitem = $item;
                $childs = self::toNested($items, $item->id);
                $nitem['childs'] = $childs;
                $results[] = $nitem;
            }
        }
        return $results;
    }

    public static function nestedMenus($lists, $parent) {
        $output = '';
        foreach ($lists as $key => $item) {
            if ($item->parent_id == $parent) {
                $output .= '<li data-id="' . $item->id . '" class="dd-item dd3-item">';
                $output.= '<div class="dd-handle dd3-handle"></div>';
                $output.= '<div class="dd3-content">'
                        . '<span class="title">' . $item->title . '</span>'
                        . '<span class="actions">'
                        . '<a href="#menu-edit-' . $item->id . '" data-toggle="collapse" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>'
                        . '</span>'
                        . '</div>'
                        . '<div id="menu-edit-' . $item->id . '" class="mi-content collapse">'
                        . '<div class="form-group"><label>' . trans('manage.title') . '</label>'
                        . Form::text('menus[' . $item->id . '][locale][title]', $item->title, ['class' => 'form-control'])
                        . '</div>'
                        . '<div class="form-group"><label>' . trans('manage.open_type') . '</label>'
                        . Form::select('menus[' . $item->id . '][open_type]', ['' => trans('manage.current_tab'), '_blank' => trans('manage.new_tab')], $item->open_type, ['class' => 'form-control'])
                        . '</div>'
                        . '<div class="form-group"><label>' . trans('manage.icon') . '</label>'
                        . Form::text('menus[' . $item->id . '][icon]', $item->icon, ['class' => 'form-control'])
                        . '</div>'
                        . '</div>';
                $output2 = self::nestedMenus($lists, $item->id);
                if ($output2 != '') {
                    $output .= '<ol class="childs dd-list">' . $output2 . '</ol>';
                }
                $output .= '</li>';
            }
        }
        return $output;
    }
    
    public function getLink($type = 'cat')
    {
        if (!$type) {
            $type = $this->type;
        }
        switch ($type) {
            case 'cat':
                $preRoute = 'cat.view';
                break;
            case 'tag':
                $preRoute = 'tag.view';
                break;
            default:
                $preRoute = null;
                break;
        }
        if ($preRoute) {
            return route('front::' . $preRoute, ['id' => $this->id, 'slug' => $this->slug]);
        }
        return null;
    }
}
