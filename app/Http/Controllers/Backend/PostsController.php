<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tag;
use App\Traits\FilterTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;

class PostsController extends Controller
{
    use FilterTrait, ImageUploadTrait;

    public function index()
    {
        $this->authorize('view-post');

        $query = Post::with(['user', 'category', 'comments'])->wherePostType('post');
        $posts = $this->filter($query);

        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');
        return view('backend.posts.index', compact('categories', 'posts'));
    }

    public function create()
    {
        $this->authorize('add-post');

        $tags = Tag::pluck('name', 'id')->toArray();
        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id')->toArray();

        return view('backend.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $this->authorize('add-post');

        $post = auth()->user()->posts()->create($request->validated() + [
                'description' => Purify::clean($request->description),
                'post_type' => 'post',
            ]);

        if ($request->images && count($request->images) > 0) {
            $this->uploadImage($request->images, $post->slug, $post);
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

        if ($request->status == 1) {
            clear_cache();
        }

        return redirect()->route('admin.posts.index')->with([
            'message' => 'Post created successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show(Post $post)
    {
        $this->authorize('view-post');

        return view('backend.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('edit-post');

        $tags = Tag::pluck('name', 'id');
        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');

        return view('backend.posts.edit', compact('categories', 'post', 'tags'));
    }

    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('edit-post');

        $post = Post::whereId($post->id)->wherePostType('post')->first();

        if ($request->images && count($request->images) > 0) {
            $this->uploadImage($request->images, $post->slug, $post);
        }

        $post->update($request->validated() + [
                'description' => Purify::clean($request->description)
        ]);

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

        return redirect()->route('admin.posts.index')->with([
            'message' => 'Post updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete-post');

        if ($post->media->count() > 0) {
            foreach ($post->media as $media) {
                if (File::exists('storage/assets/posts/' . $media->file_name)) {
                    unlink('storage/assets/posts/' . $media->file_name);
                }
            }
        }

        $post->delete();

        clear_cache();

        return redirect()->route('admin.posts.index')->with([
            'message' => 'Post deleted successfully',
            'alert-type' => 'success',
        ]);
    }

    public function removeImage(Request $request)
    {
        $this->authorize('delete-post');

        $media = PostMedia::whereId($request->media_id)->firstOrFail();

        if (File::exists('storage/assets/posts/' . $media->file_name)) {
            unlink('storage/assets/posts/' . $media->file_name);
        }

        $media->delete();

        clear_cache();

        return true;
    }
}
