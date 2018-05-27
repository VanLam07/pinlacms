<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Admin\Facades\AdConst;
use Storage;
use Image;

class File extends BaseModel
{
    protected $table = 'files';
    
    protected $fillable = ['title', 'url', 'type', 'mimetype', 'author_id', 'created_at', 'updated_at'];
    
    use SoftDeletes;
    
    public static function isUseSoftDelete() {
        return true;
    }
    
    public function author(){
        return $this->belongsTo('\App\User', 'author_id', 'id');
    }
    
    public function getSrc($size = 'full', $returnNull = true){
        $image_sizes = config('image.image_sizes');
        if(!isset($image_sizes[$size])){
            $size = 'full';
        }
        $upload_dir = trim(config('image.upload_dir'), '/');
        $src_file = $upload_dir .'/'. $size. '/' .$this->url;
        $fullSrc = $upload_dir . '/full/' . $this->url;
        if (!Storage::disk()->exists($src_file)){
            if (Storage::disk()->exists($fullSrc)) {
                return Storage::disk()->url($fullSrc);
            }
            if ($returnNull) {
                return null;
            }
        }
        return Storage::disk()->url($src_file);
    }
    
    public function getImage($size='full', $class=null, $attrs = []){
        $attrsText = '';
        if ($attrs) {
            foreach ($attrs as $key => $val) {
                $attrsText .= $key . '="'. $val .'"';
            }
        }
        if($src = $this->getSrc($size)){
            return '<img '. $attrsText .' class="img-responsive '.$class.'" src="'.$src.'" alt="No image">';
        }
        return '<img '. $attrsText .' class="img-responsive '.$class.'" src="/images/default.png" alt="No image">';
    }
    
    public static function rules($id = null) {
        $result = [
            'file' => 'mimes:jpeg,png,gif,bmp,svg|max:10240'
        ];
        if ($id) {
            $result['url'] = 'required';
        }
        return $result;
    }

    public static function getData($args = []) {
        $opts = [
            'fields' => ['*'],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => AdConst::PER_PAGE,
            'status' => [],
            'exclude_key' => 'id',
            'exclude' => [],
            'page' => 1,
            'type' => [],
            'filters' => []
        ];

        $opts = array_merge($opts, $args);
        
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

    public static function insertData($file) {
        self::validator(['file' => $file], self::rules());

        $name = $file->getClientOriginalName();
        $mimetype = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        $type = $extension;
        $cut_name = self::checkRename($name);

        $upload_dir = config('image.upload_dir', 'uploads/');

        if (in_array($extension, ['jpeg', 'jpg', 'png', 'bmp', 'gif', 'svg'])) {

            $type = 'image';
            $m_image = Image::make($file);
            $width = $m_image->width();
            $height = $m_image->height();
            $ratio = $width / $height;

            $sizes = config('image.image_sizes', [
                'thumbnail' => [
                    'width' => 80,
                    'height' => 80,
                    'crop' => true
                ],
                'medium' => [
                    'width' => 360,
                    'height' => 240,
                    'crop' => true
                ],
                'large' => [
                    'width' => 1368,
                    'height' => null,
                    'crop' => false
                ]
            ]);

            foreach ($sizes as $key => $value) {
                $w = $value['width'];
                $h = $value['height'];

                if ($w == null && $h == null) {
                    continue;
                }

                $rspath = $upload_dir . $key . '/' . $cut_name.'.'.$extension;

                $crop = $value['crop'];
                $r = ($h == null) ? 0 : $w / $h;

                if ($width > $w && $height > $h) {
                    if ($ratio > $r) {
                        $rh = $h;
                        $rw = ($h == null) ? $w : $width * $h / $height;
                    } else {
                        $rw = $w;
                        $rh = ($w == null) ? $h : $height * $w / $width;
                    }
                    $sh = round(($rh - $h) / 2);
                    $sw = round(($rw - $w) / 2);

                    $rsImage = Image::make($file)->resize($rw, $rh, function($constraint) {
                        $constraint->aspectRatio();
                    });
                    if ($crop) {
                        $rsImage->crop($w, $h, $sw, $sh);
                    }

                    Storage::disk()->put($rspath, $rsImage->stream()->__toString(), 'public');
                }
            }
        }

        $fullpath = $upload_dir . 'full/'. $cut_name.'.'.$extension;
        Storage::disk()->put($fullpath, file_get_contents($file), 'public');

        $item = new File();
        $item->title = $name;
        $item->url = $cut_name.'.'.$extension;
        $item->type = $type;
        $item->mimetype = $mimetype;
        $item->author_id = auth()->id();
        $item->save();

        return $item;
    }
    
    public static function checkRename($originalName) {
        $upload_dir = trim(config('image.upload_dir', 'uploads'), '/'); 
        $cut_name = self::cutName($originalName);
        $base_name = $cut_name['name'];
        $re_name = $base_name;
        $i = 1;
        while (Storage::disk()->exists($upload_dir . '/full/'.$re_name.'.'.$cut_name['ext'])) {
            $re_name = $base_name.'-'.$i;
            $i++;
        }
        return $re_name;
    }
    
    public static function cutName($originalName){
        $name_str = explode('.', $originalName);
        $extension = array_pop($name_str);
        return [
            'name' => str_slug(implode('.', $name_str)),
            'ext' => $extension
        ];
    }

    public static function destroyData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $sizes = config('image.image_sizes');
        $sizes['full'] = [];
        $dir = config('image.upload_dir');

        try {
            foreach ($ids as $id) {
                $image = self::find($id, ['id', 'url']);
                if ($image) {
                    foreach ($sizes as $key => $size) {
                        $path = $dir . $key . '/' . $image->url;
                        if (Storage::disk()->exists($path)) {
                            Storage::disk()->delete($path);
                        }
                    }
                    $image->delete();
                }
            }
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
