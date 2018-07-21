<?php

namespace App\Models;

use App\Models\BaseModel;
use Admin\Facades\AdConst;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel {

    protected $table = 'medias';
    protected $fillable = ['thumb_id', 'thumb_type', 'author_id', 'slider_id', 'media_type', 'media_type_id', 'status', 'target', 'views'];
    
    use SoftDeletes;
    
    public static function isUseSoftDelete() {
        return true;
    }

    public static function joinLang($lang = null) {
        if (!$lang) {
            $lang = currentLocale();
        }
        return self::join('media_desc as md', function($join) use ($lang) {
                    $join->on('medias.id', '=', 'md.media_id')
                            ->where('md.lang_code', '=', $lang);
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
    
    public function thumbnail(){
        return $this->belongsTo('\App\Models\File', 'thumb_id', 'id');
    }
    
    public function getThumbnailSrc($size = 'thumbnail') {
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
    
    /*
     * get in list
     */
    public function getImageSrc($size = 'thumbnail') {
        return AdConst::getFileSrc($this->file_url, $size);
    }
    
    public function getImage($size = 'thumbnail', $class = 'null', $attrs = []) {
        $attrsText = '';
        if ($attrs) {
            foreach ($attrs as $key => $val) {
                $attrsText .= $key . '="'. $val .'"';
            }
        }
        if($src = $this->getImageSrc($size)) {
            return '<img '. $attrsText .' class="img-responsive '.$class.'" src="'.$src.'" alt="'. $this->file_name .'">';
        }
        return '<img '. $attrsText .' class="img-responsive '.$class.'" src="/images/default.png" alt="No image">';
    }
    /*
     * end get in list
     */
    
    public static function rules($update = false) {
        if (!$update) {
            $code = currentLocale();
            return [
                $code . '.name' => 'required'
            ];
        }
        return [
            'locale.name' => 'required',
            'lang' => 'required'
        ];
    }

    public static function getData($args = []) {
        $opts = [
            'type' => 'inherit',
            'fields' => ['medias.*', 'md.*', 'file.id as file_id', 'file.url as file_url', 'file.title as file_name'],
            'status' => AdConst::STT_PUBLISH,
            'orderby' => 'medias.created_at',
            'order' => 'desc',
            'per_page' => AdConst::PER_PAGE,
            'exclude' => [],
            'albums' => [],
            'slider_id' => null,
            'filters' => []
        ];

        $opts = array_merge($opts, $args);

        $result = self::joinLang()
                ->whereNotNull('md.name')
                ->join(File::getTableName() . ' as file', 'medias.thumb_id', '=', 'file.id');;
        
        if ($opts['albums']){
            $album_ids = $opts['albums'];
            $result->join('media_tax as mt', function($join) use($album_ids){
                $join->on('mt.media_id', '=', 'medias.id')
                        ->whereIn('tax_id', $album_ids);
            });
        }
        if($opts['slider_id']){
            $result->where('medias.slider_id', $opts['slider_id']);
        }
        
        if ($opts['status']) {
            if (!is_array($opts['status'])) {
                $opts['status'] = [$opts['status']];
            }
            if ($opts['status'][0] == AdConst::STT_TRASH) {
                $result->onlyTrashed();
            } else {
                $result->whereIn('medias.status', $opts['status']);
            }
        }
        
        if ($opts['exclude']) {
            $result->whereNotIn('medias.id', $opts['exclude']);
        }
        if ($opts['filters']) {
            self::filterData($result, $opts['filters']);
        }
        $result->select($opts['fields'])
                ->orderBy($opts['orderby'], $opts['order']);

        if ($opts['per_page'] > -1) {
            return $result->paginate($opts['per_page']);
        }
        return $result->get();
    }

    public static function insertData($data, $type = 'inherit') {
        self::validator($data, self::rules());

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
        
        $langs = getLangs(['fields' => ['code']]);

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

    public static function findByLang($id, $fields = ['medias.*', 'md.*'], $lang = null) {
        $item = self::joinLang($lang)
                ->find($id, $fields);
        if (!$item) {
            $item = self::findOrFail($id);
        }
        return $item;
    }

    public static function updateData($id, $data) {
        self::validator($data, self::rules(true));

        if(isset($data['file_ids']) && $data['file_ids']){
            $data['thumb_id'] = $data['file_ids'][0];
        }
        
        $item = self::findOrFail($id);
        $fillable = $item->getFillable();
        $fill_data = array_only($data, $fillable);
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

    public static function destroyData($ids) {
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
