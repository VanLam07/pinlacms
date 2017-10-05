<?php

namespace App\Models;

use Admin\Facades\AdConst;
use App\Models\BaseModel;

class Menu extends BaseModel {

    protected $table = 'menus';
    protected $fillable = ['group_id', 'parent_id', 'menu_type', 'type_id', 'icon', 'open_type', 'order', 'status'];
    public $timestamps = false;

    public function joinLang($lang = null) {
        if (!$lang) {
            $lang = currentLocale();
        }
        return $this->join('menu_desc as md', 'menus.id', '=', 'md.menu_id')
                        ->where('md.lang_code', '=', $lang);
    }

    public function langs() {
        return $this->belongsToMany('\App\Models\Lang', 'menu_desc', 'menu_id', 'lang_code');
    }

    public function group() {
        return $this->belongsTo('App\Models\MenuCat', 'group_id', 'id');
    }

    public function getObject() {
        switch ($this->menu_type) {
            case AdConst::MENU_TYPE_CAT:
            case AdConst::MENU_TYPE_TAX:
                $object = $this->join('tax_desc as td', 'menus.type_id', '=', 'td.tax_id')
                        ->where('td.lang_code', '=', currentLocale())
                        ->where('td.tax_id', '=', $this->type_id)
                        ->first(['td.name as title', 'td.slug']);
                break;
            case AdConst::MENU_TYPE_POST:
            case AdConst::MENU_TYPE_PAGE:
                $object = $this->join('post_desc as pd', 'menus.type_id', '=', 'pd.post_id')
                        ->where('pd.lang_code', '=', currentLocale())
                        ->where('pd.post_id', '=', $this->type_id)
                        ->first(['pd.title', 'pd.slug']);
                break;
            case 0:
            default:
                $object = null;
                break;
        }
        return $object;
    }

    public function getItemRoute() {
        $route = null;
        switch ($this->menu_type) {
            case AdConst::MENU_TYPE_TAX:
                $route = 'tag.view';
                break;
            case AdConst::MENU_TYPE_CAT:
                $route = 'cat.view';
                break;
            case AdConst::MENU_TYPE_POST:
                $route = 'page.view';
                break;
            case AdConst::MENU_TYPE_PAGE:
                $route = 'post.view';
                break;
            case 0:
            default:
                break;
        }
        return $route;
    }

    public function str_status() {
        if ($this->status == 1) {
            return trans('manage.active');
        }
        return trans('manage.disable');
    }

    public function str_open_type() {
        if ($this->open_type) {
            return trans('manage.newtab_tab');
        }
        return trans('manage.current_tab');
    }

    public function getData($args = []) {
        $opts = [
            'fields' => ['menus.*', 'md.*'],
            'group_id' => -1,
            'orderby' => 'order',
            'order' => 'asc',
            'per_page' => AdConst::PER_PAGE,
            'exclude' => [],
            'lang' => currentLocale(),
            'filters' => []
        ];

        $opts = array_merge($opts, $args);

        $result = $this->joinLang($opts['lang'])
                ->whereNotNull('md.title');
        if ($opts['exclude']) {
            $result->whereNotIn('menus.id', $opts['exclude']);
        }
        if ($opts['filters']) {
            $this->filterData($result, $opts['filters']);
        }
        if ($opts['group_id'] > -1) {
            $result = $result->where('group_id', $opts['group_id']);
        }
        
        $result->select($opts['fields'])
                ->orderBy($opts['orderby'], $opts['order']);

        if ($opts['per_page'] > -1) {
            return $result->paginate($opts['per_page']);
        }
        return $result->get();
    }

    public function insertData($data) {
        if (!isset($data['order'])) {
            $data['order'] = self::max('order') + 1;
        }

        $item = self::create($data);

        $langs = getLangs(['fields' => ['code']]);
        foreach ($langs as $lang) {
            $lang_data = [
                'title' => isset($data['title']) ? $data['title'] : $data['name'],
                'link' => isset($data['link']) ? $data['link'] : ''
            ];
            $item->langs()->attach($lang->code, $lang_data);
        }
    }

    public function findCustom($id, $fields = ['md.*'], $lang = null) {
        return $this->joinLang($lang)
                        ->findOrFail($id, $fields);
    }

    public function updateData($id, $data) {

        $fillable = $this->getFillable();
        $fill_data = array_only($data, $fillable);
        $item = self::find($id);
        $item->update($fill_data);

        $lang_data = $data['locale'];
        $item->langs()->sync([$data['lang'] => $lang_data], false);
    }

    public function updateOrder($id, $order, $parent = 0) {
        $item = self::find($id);
        if ($item) {
            $item->update(['order' => $order, 'parent_id' => $parent]);
        }
    }

}
