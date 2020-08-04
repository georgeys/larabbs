<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //xss保护
        $reply->content = clean($reply->content,'user_topic_body');
    }

    //评论后统计当前文章的评论数量
    public function created(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
    }

    public function updating(Reply $reply)
    {
        //
    }
}
