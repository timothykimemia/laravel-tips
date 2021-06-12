<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function show($post_slug)
    {
        $post = Post::with(['category', 'media', 'user', 'tags',
            'approved_comments' => function ($query) {
                $query->orderBy('id', 'desc');
            }
        ])
            ->whereHas('category', function ($query) {
                $query->whereStatus(1);
            })
            ->whereHas('user', function ($query) {
                $query->whereStatus(1);
            })
            ->whereSlug($post_slug)
            ->whereStatus(1)->first();

        if ($post) {
            $blade = $post->post_type == 'post' ? 'posts' : 'pages';
            return view('frontend.' . $blade . '.show', compact('post'));
        } else {
            return redirect()->route('frontend.index');
        }
    }
}
