<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCurrentUserRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tag;
use App\Traits\AvatarUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;

class UserController extends Controller
{
    use AvatarUploadTrait;

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
                if (File::exists('storage/assets/users/' . auth()->user()->user_image)) {
                    unlink('storage/assets/users/' . auth()->user()->user_image);
                }
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

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->with([
                'message' => 'The password confirmation does not match!',
                'alert-type' => 'danger',
            ]);
        }

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.profile')->with([
                'message' => 'Information updated successfully',
                'alert-type' => 'success',
            ]);
        }

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

    public function store_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required|min:50',
            'status' => 'required',
            'comment_able' => 'required',
            'category_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['title'] = $request->title;
        $data['description'] = Purify::clean($request->description);
        $data['status'] = $request->status;
        $data['comment_able'] = $request->comment_able;
        $data['category_id'] = $request->category_id;

        $post = auth()->user()->posts()->create($data);

        if ($request->images && count($request->images) > 0) {
            $i = 1;
            foreach ($request->images as $file) {
                $filename = $post->slug . '-' . time() . '-' . $i . '.' . $file->getClientOriginalExtension();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();
                $path = storage_path('app/public/assets/posts/' . $filename);
                Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);

                $post->media()->create([
                    'file_name' => $filename,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                ]);
                $i++;
            }
        }

        if (isset($request->tags)) {
            if (count($request->tags) > 0) {
                $new_tags = [];
                foreach ($request->tags as $tag) {
                    $tag = Tag::firstOrCreate([
                        'id' => $tag
                    ], [
                        'name' => $tag
                    ]);

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
        $post = Post::whereSlug($post_id)->orWhere('id', $post_id)->whereUserId(auth()->id())->first();

        if ($post) {
            $tags = Tag::pluck('name', 'id');
            $categories = Category::whereStatus(1)->pluck('name', 'id');
            return view('frontend.users.edit_post', compact('post', 'categories', 'tags'));
        }

        return redirect()->route('frontend.index');
    }

    public function update_post(Request $request, $post_id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required|min:50',
                'status' => 'required',
                'comment_able' => 'required',
                'category_id' => 'required',
                'tags.*' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $post = Post::whereSlug($post_id)->orWhere('id', $post_id)->whereUserId(auth()->id())->first();

            if ($post) {
                $data['title'] = $request->title;
                $data['description'] = Purify::clean($request->description);
                $data['status'] = $request->status;
                $data['comment_able'] = $request->comment_able;
                $data['category_id'] = $request->category_id;

                $post->update($data);

                if ($request->images && count($request->images) > 0) {
                    $i = 1;
                    foreach ($request->images as $file) {
                        $filename = $post->slug . '-' . time() . '-' . $i . '.' . $file->getClientOriginalExtension();
                        $file_size = $file->getSize();
                        $file_type = $file->getMimeType();
                        $path = storage_path('app/public/assets/posts/' . $filename);
                        Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($path, 100);

                        $post->media()->create([
                            'file_name' => $filename,
                            'file_size' => $file_size,
                            'file_type' => $file_type,
                        ]);
                        $i++;
                    }
                }

                if (count($request->tags) > 0) {
                    $new_tags = [];
                    foreach ($request->tags as $tag) {
                        $tag = Tag::firstOrCreate([
                            'id' => $tag
                        ], [
                            'name' => $tag
                        ]);

                        $new_tags[] = $tag->id;
                    }
                    $post->tags()->sync($new_tags);
                }

                clear_cache();

                return redirect()->route('users.dashboard')->with([
                    'message' => 'Post updated successfully',
                    'alert-type' => 'success',
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function destroy_post($post_id)
    {
        try {
            $post = Post::whereSlug($post_id)->orWhere('id', $post_id)->whereUserId(auth()->id())->first();

            if ($post) {
                if ($post->media->count() > 0) {
                    foreach ($post->media as $media) {
                        if (File::exists('storage/assets/posts/' . $media->file_name)) {
                            unlink('storage/assets/posts/' . $media->file_name);
                        }
                    }
                }
                $post->delete();

                clear_cache();

                return redirect()->route('users.dashboard')->with([
                    'message' => 'Post deleted successfully',
                    'alert-type' => 'success',
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

    public function destroy_post_media($media_id)
    {
        $media = PostMedia::whereId($media_id)->first();
        if ($media) {
            if (File::exists('storage/assets/posts/' . $media->file_name)) {
                unlink('storage/assets/posts/' . $media->file_name);
            }
            $media->delete();
            clear_cache();
            return true;
        }
        return false;
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
        $comments = $comments->orderBy('id', 'desc');
        $comments = $comments->paginate(10);

        return view('frontend.users.comments', compact('comments'));
    }

    public function edit_comment($comment_id)
    {
        $comment = Comment::whereId($comment_id)->whereHas('post', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })->first();

        if ($comment) {
            return view('frontend.users.edit_comment', compact('comment'));
        } else {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }

    }

    public function update_comment(Request $request, $comment_id)
    {
        $validator = Validator::make($request->all(), [
//            'name'          => 'required',
//            'email'         => 'required|email',
//            'url'           => 'nullable|url',
            'status' => 'required',
//            'comment'       => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = Comment::whereId($comment_id)->whereHas('post', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })->first();

        if ($comment) {
//            $data['name']          = $request->name;
//            $data['email']         = $request->email;
//            $data['url']           = $request->url != '' ? $request->url : null;
            $data['status'] = $request->status;
//            $data['comment']       = Purify::clean($request->comment);

            $comment->update($data);

            clear_cache();

            return redirect()->route('users.show_comments')->with([
                'message' => 'Comment activated successfully',
                'alert-type' => 'success',
            ]);

        } else {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }

    }

    public function destroy_comment($comment_id)
    {
        $comment = Comment::whereId($comment_id)->whereHas('post', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })->first();

        if ($comment) {
            $comment->delete();

            clear_cache();

            return redirect()->back()->with([
                'message' => 'Comment deleted successfully',
                'alert-type' => 'success',
            ]);

        } else {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

}
