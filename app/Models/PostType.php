<?php

namespace App\Models;

use App\Models\Tax;
use App\Models\BaseModel;
use Admin\Facades\AdConst;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostType extends BaseModel
{
    protected $table = 'posts';
    protected static $tblDesc = 'post_desc';
    public $dates = ['trashed_at'];
    protected $fillable = ['thumb_id', 'thumb_ids', 'author_id', 'status', 'comment_status', 
        'comment_count', 'post_type', 'views', 'template', 'trased_at', 'created_at', 'updated_at'];
    
    use SoftDeletes;
    
    public static function isUseSoftDelete() {
        return true;
    }

    public static function joinLang($lang = null) {
        if (!$lang) {
            $lang = currentLocale();
        }
        return self::join(self::$tblDesc.' as pd', 'posts.id', '=', 'pd.post_id')
                        ->where('pd.lang_code', '=', $lang);
    }

    public static function joinCats($ids = []) {
        self::joinLang()
                ->join('post_tax as pt', function($join) use ($ids) {
                    $join->on('posts.id', '=', 'pt.post_id')
                    ->whereIn('tax_id', $ids);
                });
    }

    public function getCats($lang = null) {
        if (!$lang) {
            $lang = currentLocale();
        }
        return $this->belongsToMany('\App\Models\Tax', 'post_tax', 'post_id', 'tax_id')
                        ->join('tax_desc as td', 'taxs.id', '=', 'td.tax_id')
                        ->where('td.lang_code', '=', $lang)
                        ->select(['taxs.id', 'td.slug', 'td.name', 'taxs.parent_id'])
                        ->where('taxs.type', 'cat');
    }
    
    public function cats(){
        return $this->belongsToMany('\App\Models\Tax', 'post_tax', 'post_id', 'tax_id')
                ->where('taxs.type', 'cat');
    }

    public function getTags($lang = null) {
        $lang = $lang ? $lang : current_locale();
        return $this->belongsToMany('\App\Models\Tax', 'post_tax', 'post_id', 'tax_id')
                ->join('tax_desc as td', 'taxs.id', '=', 'td.tax_id')
                        ->where('td.lang_code', '=', $lang)
                        ->select(['taxs.id', 'td.slug', 'td.name', 'taxs.parent_id'])
                        ->where('taxs.type', 'tag');
    }
    
    public function tags(){
        return $this->belongsToMany('\App\Models\Tax', 'post_tax', 'post_id', 'tax_id')
                ->where('taxs.type', 'tag');
    }

    public function author() {
        return $this->belongsTo('\App\User', 'author_id', 'id')
                        ->select('id', 'name');
    }
    
    public function comments(){
        return $this->hasMany('\App\Models\Comment', 'post_id', 'id');
    }

    public function langs() {
        return $this->belongsToMany('\App\Models\Lang', 'post_desc', 'post_id', 'lang_code')
                        ->where('post_type', 'post');
    }

    public function thumbnail() {
        return $this->belongsTo('\App\Models\File', 'thumb_id', 'id');
    }
    
    public function getThumbnail($size = 'thumbnail', $class='') {
        if ($this->thumbnail) {
            return $this->thumbnail->getImage($size, $class);
        }
        return null;
    }
    
    public static function rules($update = false) {
        if (!$update) {
            $code = currentLocale();
            return [
                $code . '.title' => 'required'
            ];
        }
        return [
            'locale.title' => 'required',
            'lang' => 'required'
        ];
    }

    public static function getData($type = 'post', $args = []) {
        $opts = [
            'fields' => ['posts.*', 'pd.*'],
            'status' => [AdConst::STT_PUBLISH],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => AdConst::PER_PAGE,
            'exclude_key' => 'posts.id',
            'exclude' => [],
            'filters' => [],
            'cats' => [],
            'tags' => [],
            'with_cats' => false,
            'with_tags' => false
        ];

        $opts = array_merge($opts, $args);
        
        $result = self::joinLang();

        if ($opts['cats']) {
            $cat_ids = $this->inCats($opts['cats']);
            $result->join('post_tax as pt', function($join) use ($cat_ids) {
                $join->on('posts.id', '=', 'pt.post_id')
                        ->whereIn('tax_id', $cat_ids);
            });
        }
        if ($opts['tags']) {
            $tag_ids = $opts['tags'];
            $result->join('post_tax as pt', function($join) use ($tag_ids) {
                $join->on('posts.id', '=', 'pt.post_id')
                        ->whereIn('tax_id', $tag_ids);
            });
        }

        $result->where('post_type', $type)
                ->whereNotNull('pd.title');
        
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
            $result->whereNotIn('posts.id', $opts['exclude']);
        }
        if ($opts['filters']) {
            $this->filterData($result, $opts['filters']);
        }
        $result->select($opts['fields'])
                ->orderBy($opts['orderby'], $opts['order']);

        if ($opts['with_cats']) {
            $result->with('cats');
        }
        if ($opts['with_tags']) {
            $result->with('tags');
        }

        if ($opts['per_page'] > -1) {
            return $result->paginate($opts['per_page']);
        }
        return $result->get();
    }

    public static function insertData($data, $type = 'post') {
        self::validator($data, self::rules());

        $data['author_id'] = auth()->id();
        if (isset($data['time'])) {
            $time = $data['time'];
            $data['created_at'] = date('Y-m-d H:i:s', strtotime($time['year'] . '-' . $time['month'] . '-' . $time['day'] . ' ' . date('H:i:s')));
        }
        if (isset($data['file_ids']) && $data['file_ids']) {
            $data['thumb_id'] = $data['file_ids'][0];
        }
        if (isset($data['gallery_ids']) && $data['gallery_ids']) {
            $data['thumb_ids'] = json_encode($data['gallery_ids']);
        }
        if (!isset($data['views']) || !$data['views']) {
            $data['views'] = 0;
        }
        $data['post_type'] = $type;
        $item = self::create($data);

        $langs = getLangs(['fields' => ['code']]);

        if (isset($data['cat_ids'])) {
            $item->cats()->attach($data['cat_ids']);
            $item->cats()->increment('count');
        }

        if (isset($data['new_tags'])) {
            foreach ($data['new_tags'] as $tag) {
                $newtag = Tax::create(['type' => 'tag', 'count' => 1]);
                foreach ($langs as $lang) {
                    $tag_desc = [
                        'name' => $tag,
                        'slug' => str_slug($tag)
                    ];
                    $newtag->langs()->attach($lang->code, $tag_desc);
                }
                $item->tags()->attach($newtag->id);
            }
        }

        if (isset($data['tag_ids'])) {
            $item->tags()->attach($data['tag_ids']);
        }

        foreach ($langs as $lang) {
            $lang_data = $data[$lang->code];
            $title = $lang_data['title'];
            $slug = $lang_data['slug'];

            $lang_data['slug'] = ($slug) ? str_slug($slug) : str_slug($title);

            $item->langs()->attach($lang->code, $lang_data);
        }

        return $item;
    }

    public static function findByLang($id, $fields = ['posts.*', 'pd.*'], $lang = null) {
        $item = self::joinLang($lang)
                ->find($id, $fields);
        if ($item) {
            return $item;
        }
        return self::findOrFail($id);
    }

    public static function updateData($id, $data) {
        self::validator($data, self::rules(true));

        if (isset($data['file_ids']) && $data['file_ids']) {
            $data['thumb_id'] = $data['file_ids'][0];
        }
        if (isset($data['gallery_ids']) && $data['gallery_ids']) {
            $data['thumb_ids'] = json_encode($data['gallery_ids']);
        }
        if (isset($data['time'])) {
            $time = $data['time'];
            $data['created_at'] = date('Y-m-d H:i:s', strtotime($time['year'] . '-' . $time['month'] . '-' . $time['day'] . ' ' . date('H:i:s')));
        }
        $hasDel = false;
        if (isset($data['status']) && $data['status'] == AdConst::STT_TRASH) {
            $hasDel = true;
            unset($data['status']);
        }
        
        $item = self::findOrFail($id);
        $fillable = $item->getFillable();
        $fill_data = array_only($data, $fillable);
        $item->update($fill_data);

        $old_tags = $item->tags()->pluck('id')->toArray();
        $old_cats = $item->cats()->pluck('id')->toArray();

        $item->cats()->detach();

        if (isset($data['tag_ids'])) {
            $item->tags()->decrement('count');
            $item->tags()->attach($data['tag_ids']);
            $item->tags()->increment('count');
        } else {
            $item->tags()->attach($old_tags);
        }

        if (isset($data['cat_ids'])) {
            $item->cats()->decrement('count');
            $item->cats()->attach($data['cat_ids']);
            $item->cats()->increment('count');
        } else {
            $item->cats()->attach($old_cats);
        }

        $langs = getLangs(['fields' => ['code']]);
        if (isset($data['new_tags'])) {
            foreach ($data['new_tags'] as $tag) {
                $newtag = Tax::create(['type' => 'tag', 'count' => 1]);
                foreach ($langs as $lang) {
                    $tag_desc = [
                        'name' => $tag,
                        'slug' => str_slug($tag)
                    ];
                    $newtag->langs()->attach($lang->code, $tag_desc);
                }
                $item->tags()->attach($newtag->id);
            }
        }

        $lang_data = $data['locale'];
        $name = $lang_data['title'];
        $slug = $lang_data['slug'];
        $lang_data['slug'] = (trim($slug) == '') ? str_slug($name) : str_slug($slug);

        $item->langs()->sync([$data['lang'] => $lang_data], false);
        
        if ($hasDel) {
            $item->delete();
        }
        return $item;
    }

    public static function destroyData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        if ($ids) {
            foreach ($ids as $id) {
                $item = self::find($id);
                if ($item) {
                    $item->tags()->decrement('count');
                    $item->cats()->decrement('count');
                    $item->delete();
                }
            }
            return true;
        }
        return false;
    }

    public static function inCats($cat_ids){
        $ids = Tax::whereIn('parent_id', $cat_ids)->lists('id')->toArray();
        $result = array_merge($cat_ids, $ids);
        if($ids){
            $result = array_unique(array_merge($result, $this->inCats($ids)));
        }
        return $result;
    }
}
