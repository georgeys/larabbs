<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


	public function store(ReplyRequest $request ,Reply $reply)
	{
	    $reply->user_id  = Auth::id();
		$reply->topic_id = $request->topic_id;
        $reply->content = $request->input('content');
        $reply->save();
		return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
	}


	public function destroy(Reply $reply)
	{

		$this->authorize('destroy',$reply);
		$reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
	}
}
