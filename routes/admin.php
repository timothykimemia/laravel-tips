<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\NotificationsController as BackendNotificationsController;
use App\Http\Controllers\Backend\Auth\LoginController as BackendLoginController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\SupervisorsController;
use App\Http\Controllers\Backend\SettingsController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {

    Route::get('/login',                            [BackendLoginController::class, 'showLoginForm'])->name('show_login_form');
    Route::post('login',                            [BackendLoginController::class, 'login'])->name('login');
    Route::post('logout',                           [BackendLoginController::class, 'logout'])->name('logout');
    Route::group(['middleware' => ['auth', 'admin']], function() {

        Route::any('/notifications/get',            [BackendNotificationsController::class, 'getNotifications']);
        Route::any('/notifications/read',           [BackendNotificationsController::class, 'markAsRead']);

        Route::view('/',                             'backend.index')->name('index');
        Route::view('/profile',                             'backend.admin.profile')->name('profile');
        Route::post('/posts/removeImage/{media_id}',[PostController::class, 'removeImage'])->name('posts.media.destroy');
        Route::resource('posts',                    PostController::class);
        Route::post('/pages/removeImage/{media_id}',[PageController::class, 'removeImage'])->name('pages.media.destroy');
        Route::resource('pages',                    PageController::class);
        Route::resource('comments',            CommentController::class)->only('index', 'edit', 'update', 'destroy');
        Route::resource('categories',          CategoryController::class)->except(['show']);
        Route::resource('tags',                TagController::class);
        Route::resource('contacts',               ContactController::class);
        Route::post('/users/removeImage',            [UserController::class, 'removeImage'])->name('users.remove_image');
        Route::resource('users',                    UserController::class);
        Route::post('/supervisors/removeImage',      [SupervisorsController::class, 'removeImage'])->name('supervisors.remove_image');
        Route::resource('supervisors',              SupervisorsController::class);
        Route::resource('settings',                 SettingsController::class);
    });
});




