<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\AvatarUploadTrait;
use App\Traits\FilterTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    use FilterTrait, AvatarUploadTrait;

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
            $this->uploadAvatar($user_image);
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

        $user = User::whereId($id)->firstOrFail();

        return view('backend.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('edit-user');

        if ($avatar = $request->file('user_image')) {
            $request->validate(['user_image' => ['image', 'max:20000', 'mimes:jpeg,jpg,png']]);

            if ($user->user_image != '') {
                $this->unlinkAvatar($user->user_image);
            }

            $this->uploadAvatar($avatar);
        }

        if (trim($request->password) != '') {
            $user->update([
                'password' => bcrypt($request->password)
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
            $this->unlinkAvatar($user->user_image);
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

        $user = User::whereId($request->user_id)->firstOrFail();

        if ($user) {
            $this->unlinkAvatar($user->user_image);

            $user->user_image = null;
            $user->save();
            return 'true';
        }

        return 'false';
    }
}
