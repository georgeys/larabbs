<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /**
     * MustVerifyEmail四个方法：
    hasVerifiedEmail() 检测用户 Email 是否已认证；
    markEmailAsVerified() 将用户标示为已认证；
    sendEmailVerificationNotification() 发送 Email 认证的消息通知，触发邮件的发送；
    getEmailForVerification() 获取发送邮件地址，提供这个接口允许你自定义邮箱字段。
     */
    use MustVerifyEmailTrait,HasRoles;

    //引用notifiable 的notify （但是下面命名的一个同名的方法  所以起来一个别名）
    use Notifiable{
        notify as protected laravelNotify;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function notify($instance)
    {
       // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()){
            return;
        }

        if (method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

}
