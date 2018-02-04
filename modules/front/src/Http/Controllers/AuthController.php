<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Socialite;
use Validator;
use Auth;

class AuthController extends Controller
{
    
    use AuthenticatesUsers;
    
    protected $decayMinutes = 60;
    
    public function getRegister()
    {
        if (Auth::check()) {
            return redirect()->back();
        }
        return view('front::register');
    }
    
    public function postRegister(Request $request) {
        if (Auth::check()) {
            return redirect()->route('front::home');
        }
        
        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:5'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $role_id = DEFAULT_ROLE;
        $role = Role::where('default', 1)->first(['id']);
        if ($role) {
            $role_id = $role->id;
        }
        $user = User::create([
            'name' => $request->input('name'),
            'slug' => str_slug($request->input('name')),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        $user->roles()->attach($role_id);

        return redirect()->route('front::account.login')
                ->with('succ_mess', trans('front::message.succ_register'));
    }
    
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->back();
        }
        return view('front::login');
    }

    public function postLogin(Request $request) {
        if (Auth::check()) {
            return redirect()->route('fron:home');
        }
        $valid = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required|min:5'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        //use trait
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );

            return redirect()->back()->withInput()->with('error_mess', trans('auth.throttle', ['seconds' => $seconds]));
        }

        if ($this->attemptLogin($request)) {
            Auth::user()->storeCapsToSession();
            
            return $this->sendLoginResponse($request);
        }
        
        $this->incrementLoginAttempts($request);
        
        return redirect()->back()->withInput()->with('error_mess', trans('front::message.login_failed'));
        //end
    }
    
    public function redirectPath() 
    {
        return route('front::home');
    }
    
    public function logout(Request $request) {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }
    
    public function getForgetPass() {
        return view('front::account.forget_password');
    }

    public function postForgetPass(Request $request, User $user) {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }

        $email = $request->input('email');

        DB::beginTransaction();
        $user = User::where('email', $email)->first();
        $token = makeToken(45, $user);
        $user->resetPasswdToken = $token;
        $user->resetPasswdExpires = time() + 3600;
        $user->save();

        Mail::send('front::mail.reset_password', ['email' => $email, 'token' => $token], function($mail) use ($email) {
            $mail->from(config('mail.from.address'));
            $mail->to($email);
            $mail->subject(trans('admin::message.reset_pass_subject', ['host' => request()->getHost()]));
        });
        if (count(Mail::failures()) > 0) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_mess', trans('admin::message.failure_send_mail'));
        }

        DB::commit();
        return redirect()->back()->with('succ_mess', trans('admin::message.reset_pass_mail_sent'));
    }

    public function getResetPass(Request $request) {
        $valid = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        $error_view = view('errors.notice', ['message' => trans('admin::message.invalid_token')]);
        if ($valid->fails()) {
            return $error_view;
        }

        $token = $request->get('token');
        $user = User::where('resetPasswdToken', $token)->where('resetPasswdExpires', '>', time())->first();
        if (!$user) {
            return $error_view;
        }
        return view('front::account.reset_password', ['token' => $token]);
    }

    public function postResetPass(Request $request) {
        $valid = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }

        $token = $request->input('token');
        $user = User::where('resetPasswdToken', $token)->where('resetPasswdExpires', '>', time())->first();
        if (!$user) {
            return view('errors.notice', ['message' => trans('admin::message.invalid_token')]);
        }

        $user->password = bcrypt($request->input('password'));
        $user->resetPasswdToken = null;
        $user->resetPasswdExpires = 0;
        $user->save();

        return redirect()->route('front::account.login')->with('succ_mess', trans('admin::message.reset_pass_success'));
    }

    public function getProfile(Request $request) {
//        $account = $request->get('account');
//        if ($account) {
//            $user = User::where('slug', $account)->first();
//            if (!$user) {
//                abort(404);
//            }
//        } else {
//            $user = auth()->user();
//        }
        $user = auth()->user();
        return view('front::account.profile', compact('user'));
    }

    public function updateProfile(Request $request) {
        $valid = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        $data = $request->all();
        if (isset($data['birth'])) {
            $birth = $data['birth'];
            $parseBirth = Carbon::parse($birth['year'] . '-' . $birth['month'] . '-' . $birth['day']);
            if ($parseBirth->lte(Carbon::parse('1970-01-01 00:00:00'))) {
                $data['birth'] = '1972-01-01 00:00:00';
            } else {
                $data['birth'] = $parseBirth->format('Y-m-d H:i:s');
            }
        }
        if (isset($data['file_ids']) && $data['file_ids']) {
            $data['image_id'] = $data['file_ids'][0];
        }
        $user = User::findOrFail(auth()->id());
        $fillable = $user->getFillable();
        $fill_data = array_only($data, $fillable);
        $user->update($fill_data);
        return redirect()->back()->with('succ_mess', trans('admin::message.updated_profile'));
    }

    public function getChangePass() {
        return view('front::account.change_password');
    }

    public function updatePassword(Request $request) {
        $valid = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        $user = auth()->user();
        $check = \Hash::check($request->input('old_password'), $user->password);
        if (!$check) {
            return redirect()->back()->withInput()->with('error_mess', trans('admin::message.invalid_pass'));
        }
        User::find($user->id)->update(['password' => bcrypt($request->input('new_password'))]);
        
        return redirect()->back()->with('succ_mess', trans('admin::message.updated_pass'));
    }
    
    //social
    protected $drivers = ['facebook', 'google'];
    public function loginSocial($driver)
    {
        if (!in_array($driver, $this->drivers)) {
            return redirect()->back();
        }
        return Socialite::driver($driver)
                ->with(['driver' => $driver])->redirect();
    }
    
    public function handleLoginSocial(Request $request)
    {
        $driver = $request->get('driver');
        if (!in_array($driver, $this->drivers)) {
            return redirect()->back();
        }
        $user = Socialite::driver($driver)->user();
        dd($user);
    }
    
}
