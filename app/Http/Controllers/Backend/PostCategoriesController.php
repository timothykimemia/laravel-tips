<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostCategoriesController extends Controller
{
    use FilterTrait;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'status'        => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['name']              = $request->name;
        $data['status']             = $request->status;

        Category::create($data);

        if ($request->status == 1) {
            clear_cache();
        }

        return redirect()->route('admin.post_categories.index')->with([
            'message' => 'Category created successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = Category::whereId($id)->first();
        return view('backend.post_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'status'        => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::whereId($id)->first();

        if ($category) {
            $data['name']               = $request->name;
            $data['slug']               = null;
            $data['status']             = $request->status;


            $category->update($data);

            clear_cache();

            return redirect()->route('admin.post_categories.index')->with([
                'message' => 'Category updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect()->route('admin.post_categories.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function destroy($id)
    {
        $category = Category::whereId($id)->first();

        foreach ($category->posts as $post) {
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    if (File::exists('assets/posts/' . $media->file_name)) {
                        unlink('assets/posts/' . $media->file_name);
                    }
                }
            }
        }

        $category->delete();

        return redirect()->route('admin.post_categories.index')->with([
            'message' => 'Category deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
