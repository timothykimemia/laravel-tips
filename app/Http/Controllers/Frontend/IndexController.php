<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use App\Notifications\NewCommentForAdminNotify;
use App\Notifications\NewCommentForPostOwnerNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class IndexController extends Controller
{

    public function index()
    {
        $posts = Post::with(['media', 'user', 'tags'])
            ->whereHas('category', function ($query) {
                $query->whereStatus(1);
            })
            ->whereHas('user', function ($query) {
                $query->whereStatus(1);
            })
            ->post()->active()->orderBy('id', 'desc')->paginate(5);

        return view('frontend.index', compact('posts'));
    }

    public function search(Request $request)
    {
        $keyword = isset($request->keyword) && $request->keyword != '' ? $request->keyword : null;

        $posts = Post::with(['media', 'user', 'tags'])
            ->whereHas('category', function ($query) {
                $query->whereStatus(1);
            })
            ->whereHas('user', function ($query) {
                $query->whereStatus(1);
            });

        if ($keyword != null) {
            $posts = $posts->search($keyword, null, true);
        }

        $posts = $posts->post()->active()->orderBy('id', 'desc')->paginate(5);

        return view('frontend.index', compact('posts'));
    }

    public function category($slug)
    {
        $category = Category::whereSlug($slug)->orWhere('id', $slug)->whereStatus(1)->first()->id;

        if ($category) {
            $posts = Post::with(['media', 'user', 'tags'])
                ->whereCategoryId($category)
                ->post()
                ->active()
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('frontend.index', compact('posts'));
        }

        return redirect()->route('frontend.index');
    }

    public function tag($slug)
    {
        $tag = Tag::whereSlug($slug)->orWhere('id', $slug)->first()->id;

        if ($tag) {
            $posts = Post::with(['media', 'user', 'tags'])
                ->whereHas('tags', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                })
                ->post()
                ->active()
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('frontend.index', compact('posts'));
        }

        return redirect()->route('frontend.index');
    }

    public function archive($date)
    {
        $exploded_date = explode('-', $date);
        $month = $exploded_date[0];
        $year = $exploded_date[1];

        $posts = Post::with(['media', 'user', 'tags'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->post()
            ->active()
            ->orderBy('id', 'desc')
            ->paginate(5);
        return view('frontend.index', compact('posts'));

    }

    public function author($username)
    {
        $user = User::whereUsername($username)->whereStatus(1)->first()->id;

        if ($user) {
            $posts = Post::with(['media', 'user', 'tags'])
                ->whereUserId($user)
                ->post()
                ->active()
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('frontend.index', compact('posts'));
        }

        return redirect()->route('frontend.index');
    }









}
