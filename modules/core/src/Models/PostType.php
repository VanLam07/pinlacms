<?php

namespace App\Models;

use App\Models\Tax;
use App\Models\BaseModel;
use Admin\Facades\AdConst;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class PostType extends BaseModel
{
    protected $table = 'posts';
    protected static $tblDesc = 'post_desc';
    public $dates = ['trashed_at'];
    protected $fillable = ['thumb_id', 'thumb_ids', 'author_id', 'status', 'comment_status', 'is_feature', 'post_format',
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
                        ->select(['taxs.id', 'td.slug', 'td.name', 'taxs.parent_id', 'taxs.type'])
                        ->where('taxs.type', 'cat');
    }
    
    public function cats(){
        return $this->belongsToMany('\App\Models\Tax', 'post_tax', 'post_id', 'tax_id')
                ->where('taxs.type', 'cat');
    }

    public function getTags($lang = null) {
        $lang = $lang ? $lang : currentLocale();
        return $this->belongsToMany('\App\Models\Tax', 'post_tax', 'post_id', 'tax_id')
                ->join('tax_desc as td', 'taxs.id', '=', 'td.tax_id')
                        ->where('td.lang_code', '=', $lang)
                        ->select(['taxs.id', 'td.slug', 'td.name', 'taxs.parent_id', 'taxs.type'])
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
    
    public function authorName()
    {
        $author = $this->author;
        if ($author) {
            return $author->name;
        }
        return null;
    }
    
    public function getAuthorName()
    {
        return $this->author_name;
    }
    
    public function comments(){
        return $this->hasMany('\App\Models\Comment', 'post_id', 'id');
    }

    public function langs() {
        return $this->belongsToMany('\App\Models\Lang', 'post_desc', 'post_id', 'lang_code')
                        ->where('post_type', 'post');
    }

    /**
     * begin get in siggle post
     */
    public function thumbnail() {
        return $this->belongsTo('\App\Models\File', 'thumb_id', 'id');
    }

    public function getThumbnailSrc($size = 'full', $returnNull = true)
    {
        $thumbnail = $this->thumbnail;
        if ($thumbnail) {
            return $thumbnail->getSrc($size, $returnNull);
        }
        return null;
    }

    public function getThumbnail($size = 'thumbnail', $class='') {
        $thumbnail = $this->thumbnail;
        if ($thumbnail) {
            return $thumbnail->getImage($size, $class);
        }
        return '<img title="'. e($this->title) .'" class="img-responsive '.$class.'" src="/images/default.png" alt="No image">';
    }
    /*
     * end get in single post
     */
    
    /*
     * get in list post
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
            return '<img '. $attrsText .' class="img-responsive '.$class.'" src="'.$src.'" alt="'. $this->file_name .'" onerror="errorImage(this)">';
        }
        return '<img '. $attrsText .' class="img-responsive '.$class.'" src="/images/default.png" alt="No image">';
    }
    /*
     * end get in list post
     */

    public static function rules($update = false) {
        $rules = [
            'created_at' => 'date_format:Y-m-d H:i:s'
        ];
        if (!$update) {
            $code = currentLocale();
            $rules[$code . '.title'] = 'required';
            return $rules;
        }
        $rules['locale.title'] = 'required';
        $rules['lang'] = 'required';
        return $rules;
    }

    public static function getData($type = 'post', $args = []) {
        $opts = [
            'fields' => ['posts.*', 'pd.*', 'file.id as file_id', 'file.url as file_url', 'file.title as file_name'],
            'status' => [AdConst::STT_PUBLISH],
            'search' => null,
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'limit' => null,
            'per_page' => AdConst::PER_PAGE,
            'exclude_key' => 'posts.id',
            'is_auth' => 0,
            'is_feature' => 0,
            'exclude' => [],
            'filters' => [],
            'cats' => [],
            'post_format' => null,
            'tags' => [],
            'with_cats' => false,
            'with_tags' => false,
            'with_thumb' => true,
            'page_name' => 'page'
        ];

        $opts = array_merge($opts, $args);
        
        $result = self::joinLang()
                ->leftJoin(\App\User::getTableName() . ' as author', 'posts.author_id', '=', 'author.id');

        $hasJoinCat = false;
        if ($opts['cats']) {
            $cat_ids = self::inCats($opts['cats']);
            $result->join('post_tax as pt', function($join) use ($cat_ids) {
                $join->on('posts.id', '=', 'pt.post_id')
                        ->whereIn('pt.tax_id', $cat_ids);
            });
            $hasJoinCat = true;
        }

        if ($opts['tags']) {
            $tag_ids = $opts['tags'];
            if (!$hasJoinCat) {
                $result->join('post_tax as pt', function($join) {
                    $join->on('posts.id', '=', 'pt.post_id');
                });
            }
            $result->whereIn('pt.tax_id', $tag_ids);
        }

        $result->where('post_type', $type)
                ->whereNotNull('pd.title');

        //post format
        if ($opts['post_format']) {
            $result->where('post_format', $opts['post_format']);
        }
        if ($opts['search']) {
            $result->where('pd.title', 'like', '%' . $opts['search'] . '%');
        }
        if ($opts['status']) {
            if (!is_array($opts['status'])) {
                $opts['status'] = [$opts['status']];
            }
            if ($opts['status'][0] == AdConst::STT_TRASH) {
                $result->onlyTrashed();
            } else {
                $result->whereIn('posts.status', $opts['status']);
            }
        }
        if ($opts['is_auth']) {
            $result->where('is_auth', $opts['is_auth']);
        }
        if ($opts['is_feature']) {
            $result->where('posts.is_feature', $opts['is_feature']);
        }
        if ($opts['exclude']) {
            $result->whereNotIn('posts.id', $opts['exclude']);
        }
        if ($opts['filters']) {
            self::filterData($result, $opts['filters']);
        }
        $result->select($opts['fields'])
                ->groupBy('pd.lang_code', 'pd.post_id')
                ->orderBy($opts['orderby'], $opts['order']);

        if ($opts['with_cats']) {
            $result->leftJoin('post_tax', 'post_tax.post_id', '=', 'posts.id')
                    ->leftJoin(Tax::getTableName() . ' as cat', function ($join) {
                        $join->on('post_tax.tax_id', '=', 'cat.id')
                                ->where('cat.type', '=', 'cat');
                    })
                    ->leftJoin('tax_desc as cat_desc', function ($join) {
                        $join->on('cat.id', '=', 'cat_desc.tax_id')
                                ->where('cat_desc.lang_code', '=', currentLocale());
                    });
        }
        if ($opts['with_tags']) {
            $result->leftJoin('post_tax', 'post_tax.post_id', '=', 'posts.id')
                    ->leftJoin(Tax::getTableName() . ' as tag', function ($join) {
                        $join->on('post_tax.tax_id', '=', 'tag.id')
                                ->where('tag.type', '=', 'tag');
                    })
                    ->leftJoin('tax_desc as tag_desc', function ($join) {
                        $join->on('tag.id', '=', 'tag_desc.tax_id')
                                ->where('tag_desc.lang_code', '=', currentLocale());
                    });
        }
        if ($opts['with_tags']) {
            $result->with('tags');
        }
        if ($opts['with_thumb']) {
            $result->leftJoin(File::getTableName() . ' as file', 'posts.thumb_id', '=', 'file.id');
        }

        if ($opts['limit']) {
            return $result->take($opts['limit'])->get();
        }
        if ($opts['per_page'] > -1) {
            return $result->paginate($opts['per_page'], ['*'], $opts['page_name']);
        }
        return $result->get();
    }
    
    public function getRelated($number = 5, $type = 'post')
    {
        $cats = $this->cats->pluck('id')->toArray();
        return self::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id', 'posts.views', 'posts.post_type',
                'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content'],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'cats' => $cats,
            'exclude' => [$this->id],
            'per_page' => $number
        ]);
    }

    public static function insertData($data, $type = 'post') {
        self::validator($data, self::rules());

        $data['author_id'] = auth()->id();
        if (isset($data['file_ids']) && $data['file_ids']) {
            $data['thumb_id'] = $data['file_ids'][0];
        }
        if (isset($data['gallery_ids']) && $data['gallery_ids']) {
            $data['thumb_ids'] = json_encode($data['gallery_ids']);
        }
        if (!isset($data['views']) || !$data['views']) {
            $data['views'] = 0;
        }
        if (!isset($data['is_notify'])) {
            $data['is_notify'] = 0;
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
        $hasDel = false;
        if (isset($data['status']) && $data['status'] == AdConst::STT_TRASH) {
            $hasDel = true;
            unset($data['status']);
        }
        if (!isset($data['is_feature'])) {
            $data['is_feature'] = 0;
        }
        if (!isset($data['is_notify'])) {
            $data['is_notify'] = 0;
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
        $ids = Tax::whereIn('parent_id', $cat_ids)->pluck('id')->toArray();
        $result = array_merge($cat_ids, $ids);
        if($ids){
            $result = array_unique(array_merge($result, self::inCats($ids)));
        }
        return $result;
    }
    
    public function getLink()
    {
        switch ($this->post_type) {
            case 'post':
                return route('front::post.view', ['id' => $this->id, 'slug' => $this->slug]);
            case 'page':
                return route('front::page.view', ['id' => $this->id, 'slug' => $this->slug]);
            default:
                return null;
        }
    }

    public function getExcerpt($limit = 15, $more = '[...]')
    {
        $excerpt = $this->excerpt;
        if (!$excerpt) {
            $excerpt = $this->content;
        }
        return trimWords($excerpt, $limit, $more);
    }

    public function incrementView()
    {
        $postSessionKey = 'post_' . $this->id;
        $existsView = Session::get($postSessionKey);
        if (!$existsView) {
            Session::put($postSessionKey, 1);
            $this->increment('views');
        }
    }

    public function renderCatNames($field = 'cat_names')
    {
        if (!$this->{$field}) {
            return null;
        }
        $catNames = explode(',', $this->{$field});
        $html = '';
        foreach ($catNames as $index => $strName) {
            $arrName = explode('|', $strName);
            if (count($arrName) != 3) {
                continue;
            }
            if ($index > 1) {
                break;
            }
            $html .= '<a href="'. route('front::cat.view', ['id' => $arrName[0], 'slug' => $arrName[1]]) .'"><strong>'. e($arrName[2]) .'</strong></a>';
        }
        return $html;
    }
}
