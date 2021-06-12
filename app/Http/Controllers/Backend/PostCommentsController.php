<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class PostCommentsController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $query = Comment::query();
        $comments = $this->filter($query);

        $posts = Post::wherePostType('post')->pluck('title', 'id');
        return view('backend.post_comments.index', compact('comments', 'posts'));

    }

    public function edit($id)
    {
        $comment = Comment::whereId($id)->first();
        return view('backend.post_comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email',
            'url'           => 'nullable|url',
            'status'        => 'required',
            'comment'       => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = Comment::whereId($id)->first();

        if ($comment) {
            $data['name']           = $request->name;
            $data['email']          = $request->email;
            $data['url']            = $request->url;
            $data['status']         = $request->status;
            $data['comment']        = Purify::clean($request->comment);

            $comment->update($data);
            clear_cache();
            return redirect()->route('admin.post_comments.index')->with([
                'message' => 'Comment updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect()->route('admin.post_comments.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::whereId($id)->first();
        $comment->delete();
        clear_cache();
        return redirect()->route('admin.post_comments.index')->with([
            'message' => 'Comment deleted successfully',
            'alert-type' => 'success',
        ]);
    }

}
