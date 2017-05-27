<?php

namespace App\Modules\Adaptbb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Adaptbb\Models\Forum;
use App\Modules\Adaptbb\Models\Topic;

use Theme;

class ForumsController extends Controller
{
    public function index()
    {
        $theme = Theme::uses(Redis::get('config.theme'))->layout('front');

        $forum = new Forum;
        $forums = $forum->getIndex();

        $theme->setTitle('Community Forums');

        return $theme->scope('adaptbb.forums.index', compact('forums'))->render();
    }

    public function view($slug)
    {
        $theme = Theme::uses(Redis::get('config.theme'))->layout('front');

        $forum = Forum::where('slug', '=', $slug)->first();
        $topics = Topic::where('forum_id', '=', $forum->id)->paginate(15);

        $theme->set('meta_keywords', $forum->meta_keywords);
        $theme->set('meta_description', $forum->meta_description);
        $theme->setTitle('Community Forums - ' . $forum->name);

        return $theme->scope('adaptbb.forums.view', compact('forum', 'topics'))->render();
    }
}
