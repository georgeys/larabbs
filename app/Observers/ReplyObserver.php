<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

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
        $reply->topic->updateReplyCount();

        // 通知话题作者有新的评论
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    public function deleted(Reply $reply)
    {
       $reply->topic->updateReplyCount();
    }


    public function updating(Reply $reply)
    {
        //
    }
}
