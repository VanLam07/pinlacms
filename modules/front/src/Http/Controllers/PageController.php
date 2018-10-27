<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostType;
use App\Models\Contact;
use App\Exceptions\PlException;
use Illuminate\Support\Facades\Mail;
use Admin\Facades\AdConst;
use App\Models\Subscribe;
use Validator;

class PageController extends Controller
{
    public function index()
    {
        dd(Subscribe::cronSendMail());
        return view('front::index');
    }
    
    public function blog()
    {
        return view('front::blog');
    }
    
    public function view($slug, $id)
    {
        $page = PostType::findByLang($id);
        
        if (!$page || $page->status != AdConst::STT_PUBLISH) {
            abort(404);
        }
        $viewPage = 'front::page';
        if ($page->template) {
            $viewPage = 'front::templates.' . $page->template;
        }
        return view($viewPage, compact('page'));
    }
    
    public function sendContact(Request $request)
    {
        try {
            $data = $request->all();
            $data['ip'] = $request->ip();
            Contact::insertData($data);
            Mail::send('front::mail.new-contact', $data, function ($mail) use ($data) {
                $mail->from(config('mail.from.address'), config('mail.from.name'))
                        ->to(config('admin.email'))
                        ->subject('New contact - ' . $data['subject']);
            });
            if (Mail::failures()) {
                return redirect()->back()->withInput()->with('error_mess', trans('front::message.error_occour'));
            }
            return redirect()->back()->withInput()->with('succ_mess', trans('front::message.send_contact_successful'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
        
    }
    
    public function setVisitor(Request $request)
    {
        return \App\Models\Visitor::insertItem($request);
    }
    
    public function quoteRegister(Request $request)
    {
        $data = $request->except('_token');
        $valid = Validator::make($data, [
            'email' => 'required|email',
            'name' => 'required',
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        $type = isset($data['type']) ? $data['type'] : AdConst::FORMAT_QUOTE;
        $data['type'] = $type;
        $subs = Subscribe::where('email', $data['email'])->where('type', $type)->first();
        $data['ip'] = $request->ip();
        $data['status'] = 1;
        if (!isset($data['time']) || !$data['time']) {
            $data['time'] = '08:00';
        } else {
            $arrTime = array_map(function ($arrItem) {
                return trim($arrItem);
            }, explode(',', $data['time']));
            $validFormat = Validator::make(['times' => $arrTime], ['times.*' => 'date_format:H:i']);
            if ($validFormat->fails()) {
                return redirect()->back()->withInput()->with('error_mess', trans('front::message.invalid_time_input'));
            }
        }
        if (!$subs) {
            $subs = Subscribe::create($data);
            if ($type == AdConst::FORMAT_QUOTE) {
                $message = trans('front::message.subscribed_successful');
            } else {
                $message = trans('front::message.subscribed_dict_successful');
            }
        } else {
            $subs->update($data);
            $message = trans('front::message.subscribe_infor_updated');
        }
        
        return redirect()->back()->with('succ_mess', $message);
    }

    public function confirmRegister()
    {
        
    }
    
    public function confirmUnsubscribe($token, $type = null)
    {
        $subs = Subscribe::where('code', $token)
                ->where('type', $type)
                ->first();
        return view('front::mail.unsubscribe', compact('subs'));
    }
    
    public function unSubscribe(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->with('error_mess', trans('front::message.not_found_unsubscribe'));
        }
        $subs = Subscribe::where('code', $request->get('code'))
                ->where('type', $request->get('type'))
                ->first();
        if (!$subs) {
            return redirect()->back()->withInput()->with('error_mess', trans('front::message.not_found_unsubscribe'));
        }
        $subs->update(['status' => 2]);
        return redirect()->back()->withInput()->with('succ_mess', trans('front::message.unsubscribe_successful'));
    }
}
