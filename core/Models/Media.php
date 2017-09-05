<?php

namespace App\Models;

class Media extends BaseModel {

    protected $table = 'medias';
    protected $fillable = ['thumb_id', 'thumb_type', 'author_id', 'slider_id', 'media_type', 'media_type_id', 'status', 'target', 'views'];

    public function joinLang($lang = null) {
        $locale = ($lang) ? $lang : current_locale();
        return $this->join('media_desc as md', function($join) use ($locale) {
                    $join->on('medias.id', '=', 'md.media_id')
                            ->where('md.lang_code', '=', $locale);
                });
    }

    public function langs() {
        return $this->belongsToMany('\App\Models\Lang', 'media_desc', 'media_id', 'lang_code');
    }

    public function author() {
        return $this->belongsTo('\App\User', 'author_id', 'id')
                        ->select('id', 'name');
    }

    public function albums() {
        return $this->belongsToMany('\App\Models\Tax', 'media_tax', 'media_id', 'tax_id');
    }

    public function getAlbums($lang = null) {
        $lang = $lang ? $lang : current_locale();
        return $this->belongsToMany('\App\Models\Tax', 'media_tax', 'media_id', 'tax_id')
                        ->join('tax_desc as td', 'taxs.id', '=', 'td.tax_id')
                        ->join('langs as lg', function($join) use ($lang) {
                            $join->on('td.lang_code', '=', 'lg.code')
                            ->where('lg.code', '=', $lang);
                        })
                        ->select(['taxs.id', 'td.slug', 'td.name'])
                        ->where('taxs.type', 'album');
    }

    public function str_status() {
        if ($this->status == 1) {
            return trans('manage.enable');
        }
        return trans('manage.disable');
    }
    
    public function thumbnail(){
        return $this->belongsTo('\App\Models\File', 'thumb_id', 'id');
    }
    
    public function getThumbnailSrc($size='thumbnail') {
        if ($this->thumbnail) {
            return $this->thumbnail->getSrc($size);
        }
        return null;
    }
    
    public function getThumbnail($size = 'thumbnail', $class = 'null') {
        if ($this->thumb_type == 'image' && $this->thumbnail) {
            return $this->thumbnail->getImage($size, $class);
        }
        return null;
    }
    
    public function rules($update = false) {
        if (!$update) {
            $code = current_locale();
            return [
                $code . '.name' => 'required'
            ];
        }
        return [
            'locale.name' => 'required',
            'lang' => 'required'
        ];
    }

    public function getData($args = []) {
        $opts = [
            'type' => 'inherit',
            'fields' => ['medias.*', 'md.*'],
            'status' => 1,
            'orderby' => 'medias.created_at',
            'order' => 'desc',
            'per_page' => 20,
            'exclude' => [],
            'key' => '',
            'albums' => [],
            'slider_id' => null
        ];

        $opts = array_merge($opts, $args);

        $result = $this->joinLang();
        
        if($opts['albums']){
            $album_ids = $opts['albums'];
            $result = $result->join('media_tax as mt', function($join) use($album_ids){
                $join->on('mt.media_id', '=', 'medias.id')
                        ->whereIn('tax_id', $album_ids);
            });
        }
        if($opts['slider_id']){
            $result = $result->where('medias.slider_id', $opts['slider_id']);
        }
        $result = $result->where('medias.status', $opts['status'])
                ->whereNotNull('md.name')
                ->where('md.name', 'like', '%' . $opts['key'] . '%')
                ->whereNotIn('medias.id', $opts['exclude'])
                ->select($opts['fields'])
                ->orderBy($opts['orderby'], $opts['order']);

        if ($opts['per_page'] == -1) {
            $result = $result->get();
        } else {
            $result = $result->paginate($opts['per_page']);
        }
        return $result;
    }

    public function insertData($data, $type='inherit') {
        $this->validator($data, $this->rules());

        $data['author_id'] = auth()->id();
        if (isset($data['time'])) {
            $time = $data['time'];
            $data['created_at'] = date('Y-m-d H:i:s', strtotime($time['year'] . '-' . $time['month'] . '-' . $time['day'] . ' ' . date('H:i:s')));
        }
        if(isset($data['file_ids']) && $data['file_ids']){
            $data['thumb_id'] = $data['file_ids'][0];
        }
        $data['media_type'] = $type;
        $item = self::create($data);    
        
        $langs = get_langs(['fields' => ['id', 'code']]);

        if (isset($data['cat_ids'])) {
            $item->albums()->attach($data['cat_ids']);
            $item->albums()->increment('count');
        }

        foreach ($langs as $lang) {
            $lang_data = $data[$lang->code];
            $title = $lang_data['name'];
            $slug = isset($lang_data['slug']) ? $lang_data['slug'] : '';

            $lang_data['slug'] = ($slug) ? str_slug($slug) : str_slug($title);

            $item->langs()->attach($lang->code, $lang_data);
        }

        return $item;
    }

    public function findByLang($id, $fields = ['medias.*', 'md.*'], $lang = null) {
        $item = $this->joinLang($lang)
                ->find($id, $fields);
        if (!$item) {
            $item = $this->find($id);
        }
        return $item;
    }

    public function updateData($id, $data) {
        $this->validator($data, $this->rules(true));

        if(isset($data['file_ids']) && $data['file_ids']){
            $data['thumb_id'] = $data['file_ids'][0];
        }
        $fillable = $this->getFillable();
        $fill_data = array_only($data, $fillable);
        $item = $this->find($id);
        $item->update($fill_data);
        
        if (isset($data['cat_ids'])) {
            $item->albums()->decrement('count');
            $item->albums()->detach();
            $item->albums()->attach($data['cat_ids']);
            $item->albums()->increment('count');
        }
        
        $lang_data = $data['locale'];
        $name = $lang_data['name'];
        $slug = isset($lang_data['slug']) ? $lang_data['slug'] : '';
        $lang_data['slug'] = (trim($slug) == '') ? str_slug($name) : str_slug($slug);

        $item->langs()->sync([$data['lang'] => $lang_data], false);
    }

    public function destroyData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        if ($ids) {
            foreach ($ids as $id) {
                $item = $this->find($id);
                if ($item) {
                    $item->albums()->decrement('count');
                    $item->delete();
                }
            }
            return true;
        }
        return false;
    }

}
