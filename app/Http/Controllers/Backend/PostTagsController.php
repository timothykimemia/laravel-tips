<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostTagsController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $query = Tag::withCount('posts');
        $tags = $this->filter($query);

        return view('backend.post_tags.index', compact('tags'));

    }

    public function create()
    {
        return view('backend.post_tags.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['name'] = $request->name;

        Tag::create($data);

        clear_cache();

        return redirect()->route('admin.post_tags.index')->with([
            'message' => 'Tag created successfully',
            'alert-type' => 'success',
        ]);
    }

    public function edit($id)
    {
        $tag = Tag::whereId($id)->first();

        return view('backend.post_tags.edit', compact('tag'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tag = Tag::whereId($id)->first();

        if ($tag) {
            $data['name']               = $request->name;
            $data['slug']               = null;

            $tag->update($data);

            clear_cache();

            return redirect()->route('admin.post_tags.index')->with([
                'message' => 'Tag updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect()->route('admin.post_tags.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function destroy($id)
    {
        $tag = Tag::whereId($id)->first();
        $tag->delete();

        return redirect()->route('admin.post_tags.index')->with([
            'message' => 'Tag deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
