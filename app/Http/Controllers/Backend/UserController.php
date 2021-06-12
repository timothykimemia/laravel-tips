<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\FilterTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
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
                'receive_email' => $request->receive_email ?? 0,
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

    public function update(Request $request, $id)
    {
        $this->authorize('edit-user');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|max:20|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'mobile' => 'required|numeric|unique:users,mobile,' . $id,
            'status' => 'required',
            'password' => 'nullable|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::whereId($id)->first();

        if ($user) {
            $data['name'] = $request->name;
            $data['username'] = $request->username;
            $data['email'] = $request->email;
            $data['mobile'] = $request->mobile;
            if (trim($request->password) != '') {
                $data['password'] = bcrypt($request->password);
            }
            $data['status'] = $request->status;
            $data['bio'] = $request->bio;
            $data['receive_email'] = $request->receive_email;

            if ($user_image = $request->file('user_image')) {
                if ($user->user_image != '') {
                    if (File::exists('storage/assets/users/' . $user->user_image)) {
                        unlink('storage/assets/users/' . $user->user_image);
                    }
                }
                $filename = Str::slug($request->username) . '.' . $user_image->getClientOriginalExtension();
                $path = storage_path('app/public/assets/users/' . $filename);
                Image::make($user_image->getRealPath())->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $data['user_image'] = $filename;
            }

            $user->update($data);

            return redirect()->route('admin.users.index')->with([
                'message' => 'User updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect()->route('admin.users.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete-user');

        $user = User::whereId($id)->first();

        if ($user) {
            if ($user->user_image != '') {
                if (File::exists('storage/assets/users/' . $user->user_image)) {
                    unlink('storage/assets/users/' . $user->user_image);
                }
            }
            $user->delete();

            return redirect()->route('admin.users.index')->with([
                'message' => 'User deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->route('admin.users.index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
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
