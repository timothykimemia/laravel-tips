<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\NotificationsController as BackendNotificationsController;
use App\Http\Controllers\Backend\Auth\LoginController as BackendLoginController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\PostsController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\PostCommentsController;
use App\Http\Controllers\Backend\PostCategoriesController;
use App\Http\Controllers\Backend\PostTagsController;
use App\Http\Controllers\Backend\ContactUsController;
use App\Http\Controllers\Backend\UsersController as BackendUsersController;
use App\Http\Controllers\Backend\SupervisorsController;
use App\Http\Controllers\Backend\SettingsController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {

    Route::get('/login',                            [BackendLoginController::class, 'showLoginForm'])->name('show_login_form');
    Route::post('login',                            [BackendLoginController::class, 'login'])->name('login');
    Route::post('logout',                           [BackendLoginController::class, 'logout'])->name('logout');
    Route::group(['middleware' => ['auth', 'admin']], function() {

        Route::any('/notifications/get',            [BackendNotificationsController::class, 'getNotifications']);
        Route::any('/notifications/read',           [BackendNotificationsController::class, 'markAsRead']);

        Route::get('/',                             [AdminController::class, 'index'])->name('index');
        Route::post('/posts/removeImage/{media_id}',[PostsController::class, 'removeImage'])->name('posts.media.destroy');
        Route::resource('posts',                    PostsController::class);
        Route::post('/pages/removeImage/{media_id}',[PagesController::class, 'removeImage'])->name('pages.media.destroy');
        Route::resource('pages',                    PagesController::class);
        Route::resource('post_comments',            PostCommentsController::class)->only('index', 'edit', 'update', 'destroy');
        Route::resource('post_categories',          PostCategoriesController::class);
        Route::resource('post_tags',                PostTagsController::class);
        Route::resource('contacts',               ContactUsController::class);
        Route::post('/users/removeImage',            [BackendUsersController::class, 'removeImage'])->name('users.remove_image');
        Route::resource('users',                    BackendUsersController::class);
        Route::post('/supervisors/removeImage',      [SupervisorsController::class, 'removeImage'])->name('supervisors.remove_image');
        Route::resource('supervisors',              SupervisorsController::class);
        Route::resource('settings',                 SettingsController::class);
    });
});




