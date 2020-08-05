<?php

namespace App\Observers;


use App\Jobs\TranslateSlug;
use App\Models\Reply;
use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
        $topic->excerpt = make_excerpt($topic->body);
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        //xss 过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        $topic->excerpt = make_excerpt($topic->body);


    }

    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {

            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }

    //删掉这个主题数删除掉所有的评论
    public function deleted(Topic $topic)
    {
//        DB::table('replies')->where('topic_id',$topic->id)->delete();
        Reply::where('topic_id',$topic->id)->delete();
    }
}
