<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //获取当前用户的通知
        $notifications = Auth::user()->notifications()->paginate();
        //标记为已读 ，未读通知标记为0
        Auth::user()->markAsRead();
        return view('notifications.index',compact('notifications'));
    }

}
