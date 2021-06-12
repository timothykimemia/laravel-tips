<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostMedia;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;

class PageController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $this->authorize('view-page');

        $query = Post::wherePostType('page');
        $pages = $this->filter($query);
        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');

        return view('backend.pages.index', compact('categories', 'pages'));
    }

    public function create()
    {
        $this->authorize('add-page');

        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');

        return view('backend.pages.create', compact('categories'));
    }

    public function store(StorePageRequest $request)
    {
        $this->authorize('add-page');

        $page = auth()->user()->posts()->create($request->validated() + [
                'description' => Purify::clean($request->description),
                'post_type' => 'page',
                'comment_able' => 0,
                'category_id' => $request->category_id,
            ]);

        if ($request->images && count($request->images) > 0) {
            $i = 1;
            foreach ($request->images as $file) {
                $filename = $page->slug . '-' . time() . '-' . $i . '.' . $file->getClientOriginalExtension();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();
                $path = storage_path('app/public/assets/posts/' . $filename);
                Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);

                $page->media()->create([
                    'file_name' => $filename,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                ]);
                $i++;
            }
        }

        return redirect()->route('admin.pages.index')->with([
            'message' => 'Page created successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $this->authorize('view-page');

        $page = Post::with(['media'])->whereId($id)->wherePostType('page')->first();
        return view('backend.pages.show', compact('page'));
    }

    public function edit($id)
    {
        $this->authorize('edit-page');

        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');
        $page = Post::with(['media'])->whereId($id)->wherePostType('page')->first();

        return view('backend.pages.edit', compact('categories', 'page'));
    }

        public function update(StorePageRequest $request, $id)
    {
        $this->authorize('edit-page');

        $page = Post::whereId($id)->wherePostType('page')->first();

        $page->update($request->validated() + [
                'slug' => null,
                'description' => Purify::clean($request->description)
            ]);

        if ($request->images && count($request->images) > 0) {
            $i = 1;
            foreach ($request->images as $file) {
                $filename = $page->slug . '-' . time() . '-' . $i . '.' . $file->getClientOriginalExtension();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();
                $path = storage_path('app/public/assets/posts/' . $filename);
                Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);

                $page->media()->create([
                    'file_name' => $filename,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                ]);
                $i++;
            }
        }

        return redirect()->route('admin.pages.index')->with([
            'message' => 'Page updated successfully',
            'alert-type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete-page');

        $page = Post::whereId($id)->wherePostType('page')->first();

        if ($page) {
            if ($page->media->count() > 0) {
                foreach ($page->media as $media) {
                    if (File::exists('storage/assets/posts/' . $media->file_name)) {
                        unlink('storage/assets/posts/' . $media->file_name);
                    }
                }
            }
            $page->delete();

            clear_cache();

            return redirect()->route('admin.pages.index')->with([
                'message' => 'Page deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->route('admin.pages.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function removeImage(Request $request)
    {
        $this->authorize('delete-page');

        $media = PostMedia::whereId($request->media_id)->first();
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
}
