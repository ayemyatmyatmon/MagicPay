<?php

namespace App\Http\Controllers\Auth;

use App\Wallet;
use App\helper\UUIDGenerate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
    protected function guard()
    {
        return Auth::guard();
    }
    public function username()
    {
        return 'phone';
    }
    protected function authenticated(Request $request, $user)
    {
        $user->ip=$request->ip();
        $user->user_agent=$request->server('HTTP_USER_AGENT');
        $user->login_at=Date('Y-m-d H:i:s');
        $user->update();

        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'account_number' => UUIDGenerate::accountnumber(),
                'amount' => 0,
            ]
        );
        $user=Auth::guard('web')->user();
        $details = [
            'name' => $user->name,
            'subject' => 'Hello world',
            'body' => 'Dear ' . $user->name . ', Hello.',
        ];
    // \Mail::to($user->email)->send(new \App\Mail\LoginMail($details));
    return redirect($this->redirectTo);
    }
}
