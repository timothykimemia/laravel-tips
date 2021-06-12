<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewCommentForAdminNotify;
use App\Notifications\NewCommentForPostOwnerNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class CommentController extends Controller
{
    public function store(Request $request, $slug)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'url'       => 'nullable|url',
            'comment'   => 'required',
        ]);
        if ($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $post = Post::whereSlug($slug)->wherePostType('post')->whereStatus(1)->first();
        if ($post) {

            $userId = auth()->check() ? auth()->id() : null;
            $data['name']           = $request->name;
            $data['email']          = $request->email;
            $data['url']            = $request->url;
            $data['ip_address']     = $request->ip();
            $data['comment']        = Purify::clean($request->comment);
            $data['status']         = 1;
            $data['post_id']        = $post->id;
            $data['user_id']        = $userId;

            $comment = $post->comments()->create($data);

            if (auth()->guest() || auth()->id() != $post->user_id) {
                $post->user->notify(new NewCommentForPostOwnerNotify($comment));
            }

            User::whereHas('roles', function ($query) {
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

        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger'
        ]);

    }
}
