<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostType;
use App\Models\MailNotify;
use Validator;
use Carbon\Carbon;

class PostController extends Controller
{
    public function view($id)
    {
        $post = PostType::findByLang($id);
        $post->incrementView();
        if (!$post) {
            abort(404);
        }
        $dataNotify = null;
        if (auth()->check() && $post->is_notify) {
            $dataNotify = MailNotify::findCurrentUser($id);
        }
        return view('front::post', compact('post', 'dataNotify'));
    }
    
    public function saveMailNotify(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'post_id' => 'required',
            'email' => 'required|email|max:255'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()
                    ->withErrors($valid->errors())
                    ->with('error_mess', trans('front::message.error_occour'));
        }
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        if ($fromDate && $toDate) {
            $fromDate = Carbon::parse($fromDate);
            $toDate = Carbon::parse($toDate);
            if ($fromDate->gt($toDate)) {
                return redirect()->back()->withInput()
                        ->withErrors(['to_date' => [trans('front::message.to_date_not_less_than_from_date')]]);
            }
        }
        $postId = $request->get('post_id');
        $email = $request->get('email');
        $data = $request->except(['_token', 'post_id', 'email']);
        $data['ip'] = $request->ip();
        try {
            MailNotify::insertOrUpdate($postId, $email, $data);
            return redirect()->back()->withInput()
                    ->with('succ_mess', trans('front::message.update_successful'));
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()
                    ->with('error_mess', trans('front::message.error_occour'));
        }
    }
}
