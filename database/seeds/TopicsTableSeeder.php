<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 去除所有用户 ID 数组，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();
        $topics = factory(Topic::class)->times(50)->make()->each(function ($topic, $index) {
            if ($index == 0) {
                // $topic->field = 'value';
            }
        });

        Topic::insert($topics->toArray());
    }

}

