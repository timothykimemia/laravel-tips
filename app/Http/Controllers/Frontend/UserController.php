<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCurrentUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tag;
use App\Traits\AvatarUploadTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;

class UserController extends Controller
{
    use AvatarUploadTrait, ImageUploadTrait;

    public function get_post()
    {
        $posts = auth()->user()->posts()
            ->withCount('comments')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('frontend.users.dashboard', compact('posts'));
    }

    public function edit_info()
    {
        return view('frontend.users.edit_info');
    }

    public function update_info(UpdateCurrentUserRequest $request)
    {
        if ($avatar = $request->file('user_image')) {
            $request->validate(['user_image' => ['image', 'max:20000', 'mimes:jpeg,jpg,png']]);

            if (auth()->user()->user_image != '') {
                $this->unlinkAvatar(auth()->user()->user_image);
            }

            $filename = $this->uploadAvatar($avatar);
        }

        auth()->user()->update($request->validated() + [
                'user_image' => $filename ?? NULL
            ]);

        return redirect()->route('users.update_info')->with([
            'message' => 'Information updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function update_password(UpdatePasswordRequest $request)
    {
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->with([
                'message' => 'The password confirmation does not match!',
                'alert-type' => 'danger',
            ]);
        }

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        auth()->logout();

        return redirect()->back()->with([
            'message' => 'Password updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function create_post()
    {
        $tags = Tag::pluck('name', 'id');
        $categories = Category::whereStatus(1)->pluck('name', 'id');

        return view('frontend.users.create_post', compact('categories', 'tags'));
    }

    public function store_post(StorePostRequest $request)
    {
        $request->validate(['description' => ['required', 'min:50']]);

        $post = auth()->user()->posts()->create($request->validated() + [
                'description' => Purify::clean($request->description)
            ]);

        if ($request->images && count($request->images) > 0) {
            $request->validate(['images.*' => ['image', 'max:20000', 'mimes:jpeg,jpg,png,gif']]);
            $this->uploadImage($request->images, $post->slug, $post);
        }

        if (isset($request->tags)) {
            if (count($request->tags) > 0) {
                $new_tags = [];
                foreach ($request->tags as $tag) {
                    $tag = Tag::firstOrCreate(['id' => $tag], ['name' => $tag]);

                    $new_tags[] = $tag->id;
                }
                $post->tags()->sync($new_tags);
            }
        }

        if ($request->status == 1) {
            clear_cache();
        }

        return redirect()->route('frontend.index')->with([
            'message' => 'Post created successfully',
            'alert-type' => 'success',
        ]);

    }

    public function edit_post($post_id)
    {
        $post = Post::whereSlug($post_id)->orWhere('id', $post_id)->whereUserId(auth()->id())->firstOrFail();

        $tags = Tag::pluck('name', 'id');
        $categories = Category::whereStatus(1)->pluck('name', 'id');

        return view('frontend.users.edit_post', compact('post', 'categories', 'tags'));
    }

    public function update_post(StorePostRequest $request, $post_id)
    {
        $request->validate(['description' => ['required', 'min:50']]);

        $post = Post::whereSlug($post_id)->orWhere('id', $post_id)->whereUserId(auth()->id())->firstOrFail();

        if ($request->images && count($request->images) > 0) {
            $request->validate(['images.*' => ['image', 'max:20000', 'mimes:jpeg,jpg,png,gif']]);
            $this->uploadImage($request->images, $post->slug, $post);
        }

        $post->update($request->validated() + [
                'description' => Purify::clean($request->description)
        ]);

        if (count($request->tags) > 0) {
            $new_tags = [];
            foreach ($request->tags as $tag) {
                $tag = Tag::firstOrCreate(['id' => $tag], ['name' => $tag]);

                $new_tags[] = $tag->id;
            }
            $post->tags()->sync($new_tags);
        }

        clear_cache();

        return redirect()->route('users.get_post')->with([
            'message' => 'Post updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function destroy_post($post_id)
    {
        $post = Post::whereSlug($post_id)
            ->orWhere('id', $post_id)
            ->whereUserId(auth()->id())
            ->firstOrFail();

        if ($post->media->count() > 0) {
            foreach ($post->media as $media) {
                $this->unlinkImage($media->file_name);
            }
        }

        $post->delete();

        clear_cache();

        return redirect()->route('users.get_post')->with([
            'message' => 'Post deleted successfully',
            'alert-type' => 'success',
        ]);

    }

    public function destroy_post_media($media_id)
    {
        $media = PostMedia::whereId($media_id)->firstOrFail();

        $this->unlinkImage($media->file_name);

        $media->delete();

        clear_cache();

        return true;
    }

    public function show_comments(Request $request)
    {
        $comments = Comment::query();

        if (isset($request->post) && $request->post != '') {
            $comments = $comments->wherePostId($request->post);
        } else {
            $posts_id = auth()->user()->posts->pluck('id')->toArray();
            $comments = $comments->whereIn('post_id', $posts_id);
        }
        $comments = $comments->orderBy('id', 'desc')->paginate(10);

        return view('frontend.users.comments', compact('comments'));
    }

    public function edit_comment($comment_id)
    {
        $comment = Comment::whereId($comment_id)->whereHas('post', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })->firstOrFail();

        return view('frontend.users.edit_comment', compact('comment'));
    }

    public function update_comment(Request $request, $comment_id)
    {
        $request->validate(['status' => 'required']);

        $comment = Comment::whereId($comment_id)->whereHas('post', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })->firstOrFail();

        $comment->update(['status' => $request->status]);

        clear_cache();

        return redirect()->route('users.show_comments')->with([
            'message' => 'Comment activated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function destroy_comment($comment_id)
    {
        $this->authorize('delete-comment');

        $comment = Comment::whereId($comment_id)->whereHas('post', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })->firstOrFail();

        $comment->delete();

        clear_cache();

        return redirect()->back()->with([
            'message' => 'Comment deleted successfully',
            'alert-type' => 'success',
        ]);
    }

}
