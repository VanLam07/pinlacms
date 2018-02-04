<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostType;
use App\Models\Contact;
use App\Exceptions\PlException;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function index()
    {
        return view('front::index');
    }
    
    public function view($id)
    {
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
                $mail->from(config('mail.from.address'))
                        ->to(config('admin.email'))
                        ->subject('New contact - ' . $data['subject']);
            });
            return redirect()->back()->withInput()->with('succ_mess', trans('front::message.send_contact_successful'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
        
    }
}
