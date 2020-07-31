<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;


class UsersController extends Controller
{

    //在这个控制器中除了show方法 其它的都经过auth中间件（只有详情页面没有登录可以访问）
    public function __construct()
    {
        $this->middleware('auth',['except' => ['show']]);
    }
    //
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    public function edit(User $user)
    {
        //只可以访问当前登录账户的编辑页面
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(UserRequest $request,User $user,ImageUploadHandler $uploadHandler)
    {
        $data = $request->all();
        if ($request->avatar)
        {
            $result = $uploadHandler->save($request->avatar,'avatar',$user->id,416);
            if ($result)
            {
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功!');
    }
}
