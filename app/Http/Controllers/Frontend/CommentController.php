<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewCommentForAdminNotify;
use App\Notifications\NewCommentForPostOwnerNotify;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, $post_slug)
    {
        $post = Post::whereSlug($post_slug)->wherePostType('post')->whereStatus(1)->first();

        $comment = $post->comments()->create($request->validated() + [
                'user_id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'post_id' => $post->id,
            ]);

        if (auth()->id() != $post->user_id) {
            $post->user->notify(new NewCommentForPostOwnerNotify($comment));
        }

        User::whereHas('role', function ($query) {
            $query->whereIn('name', ['admin', 'editor']);
        })->each(function ($admin, $key) use ($comment) {
            $admin->notify(new NewCommentForAdminNotify($comment));
        });

        clear_cache();

        return redirect()->back()->with([
            'message' => 'Comment added successfully',
            'alert-type' => 'success'
        ]);
    }
}
