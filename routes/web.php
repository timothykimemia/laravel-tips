<?php

use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController as FrontendLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\Auth\VerificationController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\NotificationsController as FrontendNotificationsController;


Route::get('/',                                 [IndexController::class, 'index'])->name('frontend.index');

Route::get('/login',                            [FrontendLoginController::class, 'showLoginForm'])->name('frontend.show_login_form');
Route::post('login',                            [FrontendLoginController::class, 'login'])->name('frontend.login');

Route::get('login/{provider}',                  [FrontendLoginController::class, 'redirectToProvider'])->name('frontend.social_login');
Route::get('login/{provider}/callback',         [FrontendLoginController::class, 'handleProviderCallback'])->name('frontend.social_login_callback');

Route::post('logout',                           [FrontendLoginController::class, 'logout'])->name('frontend.logout');
Route::get('register',                          [RegisterController::class, 'showRegistrationForm'])->name('frontend.show_register_form');
Route::post('register',                         [RegisterController::class, 'register'])->name('frontend.register');
Route::get('password/reset',                    [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email',                   [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}',            [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset',                   [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('email/verify',                      [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}',         [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend',                     [VerificationController::class, 'resend'])->name('verification.resend');

Route::group(['middleware' => 'auth'], function() {
    Route::post('/comments/{post_slug}',                  [CommentController::class, 'store'])->name('frontend.comments.store')->middleware('auth');

    Route::group(['middleware' => ['verified'], 'as' => 'users.'], function () {
        Route::any('/user/notifications/get',        [FrontendNotificationsController::class, 'getNotifications']);
        Route::any('/user/notifications/read',       [FrontendNotificationsController::class, 'markAsRead']);
        Route::any('/user/notifications/read/{id}',  [FrontendNotificationsController::class, 'markAsReadAndRedirect']);

        Route::get('/user/my-post',                  [UserController::class, 'get_post'])->name('get_post');
        Route::get('/user/edit-info',                [UserController::class, 'edit_info'])->name('edit_info');
        Route::post('/user/edit-info',               [UserController::class, 'update_info'])->name('update_info');
        Route::post('/user/edit-password',           [UserController::class, 'update_password'])->name('update_password');
        Route::get('/user/show-comments',            [UserController::class, 'show_comments'])->name('show_comments');
        Route::get('/create-post',                   [UserController::class, 'create_post'])->name('post.create');
        Route::post('/create-post',                  [UserController::class, 'store_post'])->name('post.store');
        Route::get('/edit-post/{post_id}',           [UserController::class, 'edit_post'])->name('post.edit');
        Route::put('/edit-post/{post_id}',           [UserController::class, 'update_post'])->name('post.update');
        Route::delete('/delete-post/{post_id}',      [UserController::class, 'destroy_post'])->name('post.destroy');
        Route::post('/delete-post-media/{media_id}', [UserController::class, 'destroy_post_media'])->name('post.media.destroy');
        Route::get('/edit-comment/{comment_id}',     [UserController::class, 'edit_comment'])->name('comment.edit');
        Route::put('/edit-comment/{comment_id}',     [UserController::class, 'update_comment'])->name('comment.update');
        Route::delete('/delete-comment/{comment_id}',[UserController::class, 'destroy_comment'])->name('comment.destroy');
    });
});


Route::get('/contacts',                          [ContactController::class, 'create'])->name('frontend.contacts.create');
Route::post('/contacts',                         [ContactController::class, 'store'])->name('frontend.contacts.store');

Route::get('/categories/{category_slug}',        [IndexController::class, 'category'])->name('frontend.category.posts');
Route::get('/tags/{tag_slug}',                   [IndexController::class, 'tag'])->name('frontend.tag.posts');
Route::get('/archive/{date}',                    [IndexController::class, 'archive'])->name('frontend.archive.posts');
Route::get('/author/{username}',                 [IndexController::class, 'author'])->name('frontend.author.posts');
Route::get('/search',                            [IndexController::class, 'search'])->name('frontend.search');

Route::get('/posts/{post_slug}',                 [PostController::class, 'show'])->name('frontend.posts.show');





