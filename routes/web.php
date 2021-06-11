<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController as FrontendLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\Auth\VerificationController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\UsersController as FrontendUsersController;
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

Route::group(['middleware' => 'verified', 'as' => 'users.'], function () {
    Route::get('/dashboard',                    [FrontendUsersController::class, 'index'])->name('dashboard');

    Route::any('/user/notifications/get',       [FrontendNotificationsController::class, 'getNotifications']);
    Route::any('/user/notifications/read',      [FrontendNotificationsController::class, 'markAsRead']);
    Route::any('/user/notifications/read/{id}', [FrontendNotificationsController::class, 'markAsReadAndRedirect']);

    Route::get('/edit-info',                    [FrontendUsersController::class, 'edit_info'])->name('edit_info');
    Route::post('/edit-info',                   [FrontendUsersController::class, 'update_info'])->name('update_info');
    Route::post('/edit-password',               [FrontendUsersController::class, 'update_password'])->name('update_password');
    Route::get('/create-post',                  [FrontendUsersController::class, 'create_post'])->name('post.create');
    Route::post('/create-post',                 [FrontendUsersController::class, 'store_post'])->name('post.store');
    Route::get('/edit-post/{post_id}',          [FrontendUsersController::class, 'edit_post'])->name('post.edit');
    Route::put('/edit-post/{post_id}',          [FrontendUsersController::class, 'update_post'])->name('post.update');
    Route::delete('/delete-post/{post_id}',     [FrontendUsersController::class, 'destroy_post'])->name('post.destroy');
    Route::post('/delete-post-media/{media_id}',[FrontendUsersController::class, 'destroy_post_media'])->name('post.media.destroy');
    Route::get('/comments',                     [FrontendUsersController::class, 'show_comments'])->name('comments');
    Route::get('/edit-comment/{comment_id}',    [FrontendUsersController::class, 'edit_comment'])->name('comment.edit');
    Route::put('/edit-comment/{comment_id}',    [FrontendUsersController::class, 'update_comment'])->name('comment.update');
    Route::delete('/delete-comment/{comment_id}',[FrontendUsersController::class, 'destroy_comment'])->name('comment.destroy');
});

Route::get('/contact-us',                       [IndexController::class, 'contact'])->name('frontend.contact');
Route::post('/contact-us',                      [IndexController::class, 'do_contact'])->name('frontend.do_contact');
Route::get('/category/{category_slug}',         [IndexController::class, 'category'])->name('frontend.category.posts');
Route::get('/tag/{tag_slug}',                   [IndexController::class, 'tag'])->name('frontend.tag.posts');
Route::get('/archive/{date}',                   [IndexController::class, 'archive'])->name('frontend.archive.posts');
Route::get('/author/{username}',                [IndexController::class, 'author'])->name('frontend.author.posts');
Route::get('/search',                           [IndexController::class, 'search'])->name('frontend.search');
Route::get('/{post}',                           [IndexController::class, 'post_show'])->name('frontend.posts.show');
Route::post('/{post}',                          [IndexController::class, 'store_comment'])->name('frontend.posts.add_comment');



