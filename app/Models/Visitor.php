<?php

namespace App\Models;

use Carbon\Carbon;

class Visitor extends BaseModel {

    protected $table = 'visitors';
    protected $fillable = ['ip', 'agent'];
    
    public static function insertItem($request)
    {
        $ip = $request->ip();
        $agent = $request->header('User-Agent');
        $exits = self::where('ip', $ip)->orderBy('updated_at', 'desc')->first();
        if (!$exits || Carbon::now()->diffInSeconds($exits->updated_at) >= 20) {
            self::create(['ip' => $ip, 'agent' => $agent]);
        }
        return self::count('id');
    }
}
