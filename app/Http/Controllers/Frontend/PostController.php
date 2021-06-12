<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($post_slug)
    {
        $post = Post::with(['category', 'media', 'user', 'tags',
            'approved_comments' => function($query) {
                $query->orderBy('id', 'desc');
            }
        ]);

        $post = $post->whereHas('category', function ($query) {
            $query->whereStatus(1);
        })
            ->whereHas('user', function ($query) {
                $query->whereStatus(1);
            });

        $post = $post->whereSlug($post_slug);
        $post = $post->active()->first();

        if($post) {

            $blade = $post->post_type == 'post' ? 'post' : 'page';

            return view('frontend.' . $blade, compact('post'));
        } else {
            return redirect()->route('frontend.index');
        }

    }
}
