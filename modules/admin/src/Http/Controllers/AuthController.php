<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Validator;
use Mail;
use DB;
use Carbon\Carbon;

class AuthController extends Controller {
    
    protected $user;
    protected $role;

    public function __construct(User $user,Role $role) {
        $this->user = $user;
        $this->role = $role;
    }

    public function getRegister() {
        return view('admin::auth.register');
    }

    public function postRegister(Request $request) {
        $valid = Validator::make($request->all(), [
                    'name' => 'required|min:3',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:5|confirmed'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        $role_id = DEFAULT_ROLE;
        $role = $this->role->where('default', 1)->first(['id']);
        if ($role) {
            $role_id = $role->id;
        }
        $user = $this->user->create([
            'name' => $request->input('name'),
            'slug' => str_slug($request->input('name')),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        $user->roles()->attach($role_id);

        return redirect()->back()->with('succ_mess', trans('admin::message.succ_register'));
    }

    public function getLogin() {
        if (auth()->check()) {
            return view('errors.notice', ['message' => trans('admin::message.you_are_logged_in')]);
        }
        return view('admin::auth.login');
    }

    public function postLogin(Request $request) {
        $valid = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required|min:5'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }

        $auth = auth()->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ], $request->input('remember'));

        if (!$auth) {
            return redirect()->back()->withInput()->with('error_mess', trans('admin::message.login_failed'));
        }
        
        return redirect()->intended(route('admin::index'));
    }

    public function logout() {
        if (auth()->check()) {
            auth()->logout();
        }
        return redirect()->route('admin::auth.get_login');
    }

    public function getForgetPass() {
        return view('admin::auth.forget_password');
    }

    public function postForgetPass(Request $request) {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }

        $email = $request->input('email');

        DB::beginTransaction();
        $user = $this->user->where('email', $email)->first();
        $token = makeToken(45, $this->user);
        $user->resetPasswdToken = $token;
        $user->resetPasswdExpires = time() + 3600;
        $user->save();

        Mail::send('admin::mails.reset_password', ['email' => $email, 'token' => $token], function($mail) use ($email) {
            $mail->from(env('MAIL_USERNAME', 'pinla@gmail.com'), env('MAIL_NAME', 'PINLA APP'));
            $mail->to($email);
            $mail->subject(trans('admin::view.reset_pass_subject', ['host' => request()->getHost()]));
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
        $user = $this->user->where('resetPasswdToken', $token)->where('resetPasswdExpires', '>', time())->first();
        if (!$user) {
            return $error_view;
        }
        return view('admin::auth.reset_password', ['token' => $token]);
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
        $user = $this->user->where('resetPasswdToken', $token)->where('resetPasswdExpires', '>', time())->first();
        if (!$user) {
            return view('errors.notice', ['message' => trans('admin::message.invalid_token')]);
        }

        $user->password = bcrypt($request->input('password'));
        $user->resetPasswdToken = null;
        $user->resetPasswdExpires = 0;
        $user->save();

        return redirect()->route('admin::auth.get_login')->with('succ_mess', trans('admin::message.reset_pass_success'));
    }

    public function getProfile() {
        $roles = $this->role->all(['id', 'label']);
        return view('admin::account.profile', compact('roles'));
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
        $fillable = $this->user->getFillable();
        $fill_data = array_only($data, $fillable);
        $user = $this->user->findOrFail(auth()->id());
        $user->update($fill_data);
        if (isset($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.updated_profile'));
    }

    public function getChangePass() {
        return view('admin::account.change_password');
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
            return redirect()->back()->withInput()->with('error_mess', trans('auth.invalid_pass'));
        }
        if (!$this->user->find($user->id)->update(['password' => bcrypt($request->input('new_password'))])) {
            return redirect()->back()->withInput()->with('error_mess', trans('auth.error_database'));
        }
        return redirect()->back()->with('error_mess', trans('auth.updated_pass'));
    }

    public function redirectFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebook() {
        $fb_user = Socialite::driver('facebook')->user();
        $email = $fb_user->getEmail();
        $user = $this->user->where('email', $email)->first();
        if ($user) {
            if ($user->type == 'facebook') {
                auth()->login($user);
                return redirect()->intended(route('home'));
            }
            return redirect()->back()->with('error_mess', trans('auth.exists'));
        } else {
            $user = $this->user->create([
                'name' => $fb_user->getName(),
                'slug' => str_slug($fb_user->getName()),
                'type' => 'facebook',
                'email' => $fb_user->getEmail(),
                'password' => $fb_user->getId(),
                'role_id' => 3,
                'image_url' => $fb_user->getAvatar()
            ]);
            $user->remember_token = $fb_user->token;
            $user->save();
            auth()->login($user);
            return redirect()->intended(route('home'));
        }
    }
    
    public function redirectGoogle() {
        return Socialite::driver('google')->redirect();
    }
    
    public function handleGoogle() {
        $gg_user = Socialite::driver('google')->user();
        $email = $gg_user->email;
        $user = $this->user->where('email', $email)->first();
        if ($user) {
            if ($user->type == 'google') {
                auth()->login($user);
                return redirect()->intended(route('home'));
            }
            return redirect()->back()->with('error_mess', trans('auth.exists'));
        } else {
            $user = $this->user->create([
                'name' => $gg_user->getName(),
                'slug' => str_slug($gg_user->getName()),
                'type' => 'google',
                'email' => $gg_user->getEmail(),
                'password' => $gg_user->getId(),
                'role_id' => 3,
                'image_url' => $gg_user->getAvatar()
            ]);
            $user->remember_token = $gg_user->token;
            $user->save();
            auth()->login($user);
            return redirect()->intended(route('home'));
        }
    }
    
}

