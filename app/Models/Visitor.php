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
        $exits = self::where('ip', $ip)->first();
        if (!$exits || Carbon::now()->diffInSeconds($exits->updated_at) >= 5) {
            self::create(['ip' => $ip, 'agent' => $agent]);
        }
        return self::count('id');
    }
}
