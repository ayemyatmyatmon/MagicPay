<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Wallet;
use App\helper\Response;
use App\helper\UUIDGenerate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );
        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->phone=$request->phone;
        $user->password=Hash::make($request->password);
        $user->ip=$request->ip();
        $user->user_agent=$request->server('HTTP_USER_AGENT');
        $user->login_at=Date('Y-m-d H:i:s');
        $user->save();
        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'account_number' => UUIDGenerate::accountnumber(),
                'amount' => 0,
            ]
        );
        $token = $user->createToken('Magic Pay')->accessToken;
        return Response::success('Successfully Register.',['token'=>$token]);
    }
    public function login(Request $request){
        $request->validate(
            [
                'phone' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8'],
            ]
            );



        if(Auth::attempt(['phone'=>$request->phone,'password'=>$request->password])){
            $user=auth()->user();

            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'account_number' => UUIDGenerate::accountnumber(),
                    'amount' => 0,
                ]
            );
            $user->ip=$request->ip();
            $user->user_agent=$request->server('HTTP_USER_AGENT');
            $user->login_at=Date('Y-m-d H:i:s');
            $user->update();


            $token = $user->createToken('Magic Pay')->accessToken;

            return Response::success('Successfully Login',['token'=>$token]);

            }
            return Response::fail('These credentials do not match our records.',null);
    }
    public function Logout(){
        $user=auth::user();
        $user->token()->revoke();
        return Response::success('successfully logout',null);
    }
}
