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
        $uploadDir = trim(config('image.upload_dir'), '/');
        if(!isset($image_sizes[$size])){
            $size = 'full';
        }
        $fullDir = $uploadDir . '/full/' . $this->url;
        if ($size == 'full') {
            $cloudFile = static::getCloudFile($this->url);
            if ($cloudFile) {
                if (!Storage::disk()->exists($fullDir)) {
                    $srcFile = Storage::cloud()->url($cloudFile['path']);
                } else {
                    $srcFile = Storage::disk()->url($fullDir);
                }
                return $srcFile;
            }
        }
        $srcFile = $uploadDir .'/'. $size. '/' .$this->url;
        $thumbDir = $uploadDir . '/thumbnail/' . $this->url;
        if (!Storage::disk()->exists($srcFile)){
            if (Storage::disk()->exists($fullDir)) {
                return Storage::disk()->url($fullDir);
            }
            if (Storage::disk()->exists($thumbDir)) {
                return Storage::disk()->url($thumbDir);
            }
            if ($returnNull) {
                return null;
            }
        }
        return Storage::disk()->url($srcFile);
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
        $cutName = self::checkRename($name);

        $uploadDir = trim(config('image.upload_dir', 'uploads/'), '/');

        if (in_array($extension, ['jpeg', 'jpg', 'png', 'bmp', 'gif', 'svg'])) {

            $type = 'image';
            $mImage = Image::make($file);
            $width = $mImage->width();
            $height = $mImage->height();
            $ratio = $width / $height;

            $sizes = config('image.image_sizes');

            foreach ($sizes as $key => $value) {
                $w = $value['width'];
                $h = $value['height'];

                if ($w == null && $h == null) {
                    continue;
                }

                $rspath = $uploadDir . '/' . $key . '/' . $cutName.'.'.$extension;

                $crop = $value['crop'];
                $r = ($h == null) ? 0 : $w / $h;

                if (($width < $w || $height < $h) && ($key != 'thumbnail')) {
                    continue;
                }

                if (($width < $w || $height < $h)) {
                    Storage::disk()->put($rspath, file_get_contents($file), 'public');
                    continue;
                }
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

        $driveUploadDir = self::hasDir($uploadDir);
        if (!$driveUploadDir) {
            Storage::cloud()->createDir($uploadDir);
            $driveUploadDir = self::hasDir($uploadDir);
        }
        $fullDir = self::hasDir('full', '/' . $driveUploadDir['path']);
        if (!$fullDir) {
            Storage::cloud()->createDir($driveUploadDir['path'] . '/full');
            $fullDir = self::hasDir('full', '/' . $driveUploadDir['path']);
        }
        $fullpath = $fullDir['path'] . '/' . $cutName.'.'.$extension;
        Storage::cloud()->put($fullpath, file_get_contents($file));

        $item = new File();
        $item->title = $name;
        $item->url = $cutName.'.'.$extension;
        $item->type = $type;
        $item->mimetype = $mimetype;
        $item->author_id = auth()->id();
        $item->save();

        return $item;
    }
    
    public static function hasDir($dir, $root = '/')
    {
        $recursive = false;
        $contents = collect(Storage::cloud()->listContents($root));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $dir)
            ->first();
        if (!$dir) {
            return false;
        }
        return $dir;
    }
    
    public static function checkRename($originalName) {
        $uploadDir = trim(config('image.upload_dir', 'uploads'), '/'); 
        $cutName = self::cutName($originalName);
        $base_name = $cutName['name'];
        $reName = $base_name;
        $i = 1;
        while (Storage::disk()->exists($uploadDir . '/thumbnail/'.$reName.'.'.$cutName['ext'])) {
            $reName = $base_name.'-'.$i;
            $i++;
        }
        return $reName;
    }
    
    public static function cutName($originalName){
        $name_str = explode('.', $originalName);
        $extension = array_pop($name_str);
        return [
            'name' => str_slug(implode('.', $name_str)),
            'ext' => $extension
        ];
    }

    public function forceDelete() {
        $sizes = config('image.image_sizes');
        $dir = trim(config('image.upload_dir'), '/');

        try {
            $image = $this;
            $filename = $image->url;
            foreach ($sizes as $key => $size) {
                $path = $dir . $key . '/' . $filename;
                if (Storage::disk()->exists($path)) {
                    Storage::disk()->delete($path);
                }
            }
            \DB::table($this->getTable())->where('id', $image->id)->delete();
            //delete full size
            $file = static::getCloudFile($filename);
            if ($file) {
                Storage::cloud()->delete($file['path']);
            }
            return true;
        } catch (\Exception $ex) {
            dd($ex);
            return false;
        }
    }

    public static function getCloudFile($filename)
    {
        $dir = trim(config('image.upload_dir'), '/');
        $uploadDir = static::hasDir($dir);
            if (!$uploadDir) {
                Storage::cloud()->createDir($dir);
                $uploadDir = static::hasDir($dir);
            }
            $fullDir = self::hasDir('full', '/' . $uploadDir['path']);
            if (!$fullDir) {
                Storage::cloud()->createDir($uploadDir['path'] . '/full');
                $fullDir = self::hasDir('full', '/' . $uploadDir['path']);
            }
            $contents = collect(Storage::cloud()->listContents($fullDir['path']));
            return $contents
                ->where('type', '=', 'file')
                ->where('name', '=', $filename)
                ->first();
    }
}
