<?php

namespace App\Http\Controllers\Manager;

use App\Models\Manager;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo;

    public function __construct()
    {
        $this->redirectTo = '/manager/dash';
    }

    public function showLoginForm()
    {
        return view('manager.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $request->attributes->set('status_error', 0);
        $request->attributes->set('captcha_error', 0);
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        $request->attributes->set('error', '账号/密码错误');

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = ['error' => $request->attributes->get('error')];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    protected function attemptLogin(Request $request)
    {
        $params = $this->credentials($request);
        //$params['type'] = 4;
        return $this->guard()->attempt(
            $params, $request->has('remember')
        );
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/manager');
    }

    protected function guard()
    {
        return auth()->guard('manager');
    }

    public function username()
    {
        return 'username';
    }

    public function secure(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'old_password' => ['required'],
                'password' => 'required|min:6|confirmed',
            ], [
                'old_password.required'  => '请输入旧密码',
                'password.required'  => '请输入新密码',
                'password.min'  => '新密码最少6位',
                'password.confirmed'  => '确认密码有误，请重新确认密码',
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            if (!Hash::check($request->input('old_password'), auth('manager')->user()->password)) {
                return ['success' => false,'msg'=>'旧密码错误'];
            }

            if (Manager::query()->where('id', auth('manager')->user()->id)->update(['password'=>bcrypt($request->input('password'))])) {
                return ['success' => true,'msg'=>'修改成功'];
            }
            return ['success' => false,'msg'=>'修改失败'];
        }
        return view('manager.secure');
    }

    /**
     * 自定义快捷登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function diyLogin(Request $request)
    {
        Auth::guard('manager')->loginUsingId(1, true);
        return redirect(route('manager.index'));
    }
}
