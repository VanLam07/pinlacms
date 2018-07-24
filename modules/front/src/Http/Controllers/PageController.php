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
        return view('front::index');
    }
    
    public function blog()
    {
        return view('front::blog');
    }
    
    public function view($slug, $id)
    {
        dd(Subscribe::cronSendMail());
        $page = PostType::findByLang($id);
        
        if (!$page) {
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
            'email' => 'required|email|unique:' . Subscribe::getTableName() . ',email',
            'name' => 'required',
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid->errors());
        }
        $data['ip'] = $request->ip();
        $data['type'] = AdConst::FORMAT_QUOTE;
        Subscribe::create($data);
        return redirect()->back()->with('succ_mess', trans('front::message.subscribed_successful'));
    }

    public function confirmRegister()
    {
        
    }
}
