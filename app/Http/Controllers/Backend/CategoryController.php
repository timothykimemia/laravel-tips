<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Traits\FilterTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    use FilterTrait, ImageUploadTrait;

    public function index()
    {
        $query = Category::withCount('posts');
        $categories = $this->filter($query);

        return view('backend.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.post_categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        if ($request->status == 1) {
            clear_cache();
        }

        return redirect()->route('admin.categories.index')->with([
            'message' => 'Category created successfully',
            'alert-type' => 'success'
        ]);
    }

    public function edit(Category $category)
    {
        return view('backend.post_categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        clear_cache();

        return redirect()->route('admin.categories.index')->with([
            'message' => 'Category updated successfully',
            'alert-type' => 'success',
        ]);

    }

    public function destroy(Category $category)
    {
        foreach ($category->posts as $post) {
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    $this->unlinkImage($media->file_name);
                }
            }
        }

        $category->delete();

        clear_cache();

        return redirect()->route('admin.categories.index')->with([
            'message' => 'Category deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
