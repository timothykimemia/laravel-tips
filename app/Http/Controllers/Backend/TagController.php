<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use App\Traits\FilterTrait;

class TagController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $this->authorize('view-tag');

        $query = Tag::withCount('posts');
        $tags = $this->filter($query);

        return view('backend.post_tags.index', compact('tags'));

    }

    public function create()
    {
        $this->authorize('add-tag');

        return view('backend.post_tags.create');
    }

    public function store(StoreTagRequest $request)
    {
        Tag::create($request->validated());

        clear_cache();

        return redirect()->route('admin.tags.index')->with([
            'message' => 'Tag created successfully',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Tag $tag)
    {
        return view('backend.post_tags.edit', compact('tag'));
    }

    public function update(StoreTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        clear_cache();

        return redirect()->route('admin.tags.index')->with([
            'message' => 'Tag updated successfully',
            'alert-type' => 'success',
        ]);

    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        clear_cache();

        return redirect()->route('admin.tags.index')->with([
            'message' => 'Tag deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
