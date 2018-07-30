<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Admin\Facades\AdConst;
use App\Models\PostType;
use Mail;

class Subscribe extends BaseModel
{
    protected $table = 'subscribes';
    protected $fillable = ['email', 'name', 'ip', 'type', 'time'];

    public static function cronSendMail()
    {
        $timeNow = Carbon::now();
        $partTime = Carbon::now()->subHours(6);
        $collect = self::select('email', 'name')
                ->where(function ($query) use ($timeNow, $partTime) {
                    $query->where(function ($query1) use ($timeNow) {
                            $query1->where(DB::raw('HOUR(time)'), $timeNow->hour)
                                ->where(DB::raw('MINUTE(time)'), $timeNow->minute);
                    })
                    ->orWhere(function ($query2) use ($partTime) {
                            $query2->where(DB::raw('HOUR(time)'), $partTime->hour)
                                ->where(DB::raw('MINUTE(time)'), $partTime->minute);
                    });
                })
                ->where('type', AdConst::FORMAT_QUOTE)
                ->get();

        if ($collect->isEmpty()) {
            return;
        }
        $tblPrefix = DB::getTablePrefix();
        $quote = PostType::joinLang()
                ->select('posts.id', 'pd.content', 'pd.slug')
                ->where(DB::raw('DATE('. $tblPrefix . 'posts.created_at)'), $timeNow->toDateString())
                ->where('posts.post_format', AdConst::FORMAT_QUOTE)
                ->orderBy('posts.created_at', 'desc')
                ->first();

        if (!$quote) {
            return;
        }

        foreach ($collect as $item) {
            $dataMail = [
                'dearName' => $item->name,
                'content' => $quote->content,
                'detailLink' => route('front::post.view', ['id' => $quote->id, 'slug' => $quote->slug])
            ];
            Mail::send('front::mail.quote-alert', $dataMail, function ($mail) use ($item, $timeNow) {
                $mail->to($item->email)
                        ->subject(trans('front::view.quote_mail_alert_subject', ['date' => $timeNow->format('d-m-Y')]));
            });
        }
    }
}
