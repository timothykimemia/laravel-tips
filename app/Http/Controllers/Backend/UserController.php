<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\FilterTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $this->authorize('view-user');

        $query = User::whereHas('role', function ($query) {
            $query->where('name', 'user');
        });
        $users = $this->filter($query);

        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('add-user');

        return view('backend.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('add-user');

        if ($user_image = $request->file('user_image')) {
            $filename = Str::slug($request->username) . '.' . $user_image->getClientOriginalExtension();
            $path = storage_path('app/public/assets/users/' . $filename);
            Image::make($user_image->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
        }

        User::create($request->validated() + [
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt($request->password),
                'user_image' => $filename ?? null
            ]);

        return redirect()->route('admin.users.index')->with([
            'message' => 'Users created successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $this->authorize('view-user');

        $user = User::whereId($id)->withCount('posts')->firstOrFail();

        return view('backend.users.show', compact('user'));
    }

    public function edit($id)
    {
        $this->authorize('edit-user');

        $user = User::whereId($id)->first();
        if ($user) {
            return view('backend.users.edit', compact('user'));
        }
        return redirect()->route('admin.users.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('edit-user');

        if ($avatar = $request->file('user_image')) {
            $request->validate(['user_image' => ['image', 'max:20000', 'mimes:jpeg,jpg,png']]);

            if ($user->user_image != '') {
                if (File::exists('storage/assets/users/' . $user->user_image)) {
                    unlink('storage/assets/users/' . $user->user_image);
                }
            }
            $filename = Str::slug($request->username) . '.' . $avatar->getClientOriginalExtension();
            $path = storage_path('app/public/assets/users/' . $filename);
            Image::make($avatar->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
        }

        if (trim($request->password) != '') {
            $user->update([
                'password' =>  bcrypt($request->password)
            ]);
        }

        $user->update($request->validated() + [
                'user_image' => $filename ?? NULL,
            ]);

        return redirect()->route('admin.users.index')->with([
            'message' => 'User updated successfully',
            'alert-type' => 'success',
        ]);

    }

    public function destroy(User $user)
    {
        $this->authorize('delete-user');

        if ($user->user_image != '') {
            if (File::exists('storage/assets/users/' . $user->user_image)) {
                unlink('storage/assets/users/' . $user->user_image);
            }
        }
        $user->delete();

        clear_cache();

        return redirect()->route('admin.users.index')->with([
            'message' => 'User deleted successfully',
            'alert-type' => 'success',
        ]);

    }

    public function removeImage(Request $request)
    {
        $this->authorize('delete-user');

        $user = User::whereId($request->user_id)->first();

        if ($user) {
            if (File::exists('storage/assets/users/' . $user->user_image)) {
                unlink('storage/assets/users/' . $user->user_image);
            }
            $user->user_image = null;
            $user->save();
            return 'true';
        }
        return 'false';
    }
}
