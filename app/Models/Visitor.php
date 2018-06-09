<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;

class Visitor extends BaseModel {

    protected $table = 'visitors';
    protected $fillable = ['ip', 'agent', 'lang'];
    
    public static function insertItem($request)
    {
        $ip = $request->ip();
        $sessionKey = 'visitor_' . $ip;
        if (!Session::get($sessionKey)) {
            Session::put($sessionKey, 1);
            self::create([
                'ip' => $ip,
                'agent' => $request->header('User-Agent'),
                'lang' => $request->getPreferredLanguage()
            ]);
        }
        return self::count('id');
    }
}
