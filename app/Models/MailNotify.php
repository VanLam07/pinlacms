<?php

namespace App\Models;

use Admin\Facades\AdConst;
use App\Models\BaseModel;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Cookie;

class MailNotify extends BaseModel
{
    protected $table = 'post_notify';
    protected $fillable = ['post_id', 'email', 'ip', 'from_date', 'to_date', 'from_hour', 'to_hour', 'number_alert', 'array_time'];

    public static function isUseSoftDelete()
    {
        return false;
    }
    
    public static function getByEmail($postId, $email)
    {
        return self::where('post_id', $postId)
                ->where('email', $email)
                ->first();
    }
    
    public static function getArrayTimeInDay($numAlert, $fromHour = null, $toHour = null)
    {
        if (!$numAlert) {
            return null;
        }
        if ($fromHour === null) {
            $fromHour = '00:00';
        }
        if ($toHour === null) {
            $toHour = '23:59';
        }
        $fromHour = Carbon::createFromFormat('H:i', $fromHour);
        $toHour = Carbon::createFromFormat('H:i', $toHour);
        if ($toHour->lt($fromHour)) {
            $toHour->addHours(24);
        }
        $diffTime = $toHour->diff($fromHour);
        $diffHour = $diffTime->h;
        $diffMinute = $diffTime->i;
        if ($diffHour === 0 && $diffMinute === 0) {
            return null;
        }
        $diffTotalMinute = $diffHour * 60 + $diffMinute;
        $stepMinute = round($diffTotalMinute / ($numAlert + 1));
        $arrTime = [];
        $toHour->subMinutes($stepMinute);
        while ($fromHour->lt($toHour)) {
            $fromHour->addMinutes($stepMinute);
            $arrTime[] = $fromHour->format('H:i');
        }
        return json_encode($arrTime);
    }
    
    public static function insertOrUpdate($postId, $email, $data = [])
    {
        $data['array_time'] = self::getArrayTimeInDay($data['number_alert'], $data['from_hour'], $data['to_hour']);
        
        $item = self::where('post_id', $postId)
                ->where('email', $email)
                ->first();
        if (!$item) {
            $data['post_id'] = $postId;
            $data['email'] = $email;
            $item = self::create($data);
        } else {
            $item->update($data);
        }
        return $item;
    }
    
    public static function findCurrentUser($postId)
    {
        $item = self::where('post_id', $postId)
                ->where('email', auth()->user()->email)
                ->first();
        if (!$item) {
            return self::where('post_id', $postId)
                    ->where('ip', request()->ip())
                    ->orderBy('updated_at', 'desc')
                    ->first();
        }
        return $item;
    }
    
    public static function cronAlert()
    {
        $timeNow = Carbon::now();
        $timeFox = Carbon::now()->endOfDay();
        $postData = self::select('pnt.from_date', 'pnt.to_date', 'pnt.from_hour', 'pnt.to_hour', 'pnt.number_alert',
                'pnt.array_time', 'pnt.email', 'pd.title', 'pd.content')
                ->from(self::getTableName() . ' as pnt')
                ->join('post_desc as pd', 'pnt.post_id', '=', 'pd.post_id')
                ->where('pd.lang_code', currentLocale())
                ->where('pnt.from_date', '<=', $timeFox->toDateString())
                ->where('pnt.to_date', '>=', $timeFox->startOfDay()->toDateString())
                ->groupBy('pnt.id')
                ->get();
        
        if ($postData->isEmpty()) {
            return;
        }
        foreach ($postData as $item) {
            if (!$item->array_time) {
                continue;
            }
            $arrayTime = json_decode($item->array_time, true);
            foreach ($arrayTime as $time) {
                $itemTime = Carbon::createFromFormat('H:i', $time);
                if ($timeNow->hour === $itemTime->hour && $timeNow->minute === $itemTime->minute) {
                    dump('send');
                    $dataPost = [
                        'postTitle' => $item->title,
                        'postContent' => $item->content
                    ];
                    Mail::send('front::mail.post-alert', $dataPost, function ($mail) use ($item) {
                        $mail->to($item->email)
                                ->subject(trans('front::view.post_mail_alert_subject') . ' ' . $item->title);
                    });
                    break;
                }
            }
        }
    }
}
