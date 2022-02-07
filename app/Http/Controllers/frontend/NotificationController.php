<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){
        $authuser=Auth::guard('web')->user();
        $notifications=$authuser->notifications;
        return view('frontend.notification',compact('notifications'));
    }
    public function show($id){
        $authuser=Auth::guard('web')->user();
        $notification=$authuser->notifications()->where('id',$id)->first();
        $notification->markAsRead();

        return view('frontend.show',compact('notification'));
    }
}
