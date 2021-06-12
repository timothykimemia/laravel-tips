<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\FilterTrait;
use Stevebauman\Purify\Facades\Purify;

class CommentController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $query = Comment::query();
        $comments = $this->filter($query);

        $posts = Post::wherePostType('post')->pluck('title', 'id');
        return view('backend.post_comments.index', compact('comments', 'posts'));

    }

    public function edit(Comment $comment)
    {
        return view('backend.post_comments.edit', compact('comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated() + [
                'comment' => Purify::clean($request->comment)
            ]);

        clear_cache();
        return redirect()->route('admin.comments.index')->with([
            'message' => 'Comment updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        clear_cache();

        return redirect()->route('admin.comments.index')->with([
            'message' => 'Comment deleted successfully',
            'alert-type' => 'success',
        ]);
    }

}
