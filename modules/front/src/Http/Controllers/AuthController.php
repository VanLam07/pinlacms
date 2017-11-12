<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Socialite;

class AuthController extends Controller
{
    
    use AuthenticatesUsers;
    
    protected $decayMinutes = 60;
    
    public function postRegister(Request $request) {
        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:5'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $role_id = DEFAULT_ROLE;
        $role = $this->role->where('default', 1)->first(['id']);
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

        return redirect()->route('front::index')
                ->with('succ_mess', trans('admin::message.succ_register'));
    }

    public function postLogin(Request $request) {
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
