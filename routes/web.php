<?php

use App\Livewire\LandingArea\Welcome;
use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardArea\Profile;
use App\Http\Controllers\HomeController;
use App\Livewire\DashboardArea\Contactus;
use App\Livewire\DashboardArea\Dashboard;
use App\Livewire\DashboardArea\Categories;
use App\Http\Controllers\PaymentController;
use App\Livewire\LandingArea\PaymentStatus;
use App\Livewire\LandingArea\Blog\BlogIndex;
use App\Livewire\DashboardArea\Blog\EditPost;
use App\Livewire\LandingArea\Blog\BlogSingle;
use App\Livewire\DashboardArea\Blog\ListPosts;
use App\Livewire\DashboardArea\Meals\EditMeal;
use App\Livewire\DashboardArea\Meals\ListFood;
use App\Livewire\DashboardArea\Blog\CreatePost;
use App\Livewire\DashboardArea\Meals\ListMeals;
use App\Livewire\DashboardArea\Orders\Payments;
use App\Livewire\DashboardArea\Meals\CreateMeal;
use App\Livewire\DashboardArea\Orders\EditOrder;
use App\Livewire\DashboardArea\Orders\ViewOrder;
use App\Livewire\DashboardArea\Blog\ListComments;
use App\Livewire\DashboardArea\Orders\ListOrders;
use App\Livewire\LandingArea\Policies\Disclaimer;
use App\Livewire\DashboardArea\Orders\CreateOrder;
use App\Livewire\LandingArea\Policies\PrivacyPolicy;
use App\Livewire\LandingArea\Policies\DigitalMillenium;
use App\Livewire\LandingArea\Policies\TermsAndConditions;
use App\Livewire\DashboardArea\Notifications\ListNotifications;
use App\Livewire\LandingArea\Policies\PaymentDisputeChargebacks;


Route::get('/', Welcome::class)->name('index');
Route::get('blog', BlogIndex::class)->name('blog');
Route::get('blog/post/{post}', BlogSingle::class)->name('blog.show');

Route::group(['as'=> 'legal.'],function(){
    Route::get('disclaimer', Disclaimer::class)->name('disclaimer');
    Route::get('privacy-policy', PrivacyPolicy::class)->name('privacy-policy');
    Route::get('terms-and-conditions', TermsAndConditions::class)->name('terms-conditions');
    Route::get('digital-millenium-copyright-act', DigitalMillenium::class)->name('dcma');
    Route::get('payment-dispute-chargeback-protection-policy', PaymentDisputeChargebacks::class)->name('payment-chargeback');
});


Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => ['email_verified','two_factor']], function () {
        
        Route::get('dashboard', Dashboard::class)->name('dashboard');
        Route::get('profile', Profile::class)->name('profile');
        Route::get('categories', Categories::class)->name('categories');
        Route::group(['prefix' => 'meals','as' => 'meals.'], function () {
            Route::get('/', ListMeals::class)->name('index');
            Route::get('create', CreateMeal::class)->name('create');
            Route::get('edit/{meal}', EditMeal::class)->name('edit');
        });
        
        Route::group(['prefix' => 'food','as' => 'food.'], function () {
            Route::get('/', ListFood::class)->name('index');
        });

        Route::group(['prefix' => 'posts','as' => 'posts.'], function () {
            Route::get('/', ListPosts::class)->name('index');
            Route::get('create', CreatePost::class)->name('create');
            Route::get('edit/{post}', EditPost::class)->name('edit');
            Route::get('comment', ListComments::class)->name('comments');  
        });

        Route::group(['prefix' => 'orders','as' => 'orders.'], function () {
            Route::get('/', ListOrders::class)->name('index');
            Route::get('create', CreateOrder::class)->name('create');
            Route::get('edit/{order}', EditOrder::class)->name('edit');
            Route::get('view/{order}',ViewOrder::class)->name('view'); 
        });
        Route::group(['prefix' => 'payments','as' => 'payments.'], function () {
            Route::get('/',Payments::class)->name('index');
        });

        Route::get('notifications', ListNotifications::class)->name('notifications');
        Route::get('contact', Contactus::class)->name('contact');
        Route::get('payment/callback',[PaymentController::class,'paymentcallback'])->name('payment.callback');
        Route::get('payment/status/{payment}',PaymentStatus::class)->name('payment.status');
        
    });
});


require __DIR__.'/auth.php';

Route::get('/home', [HomeController::class, 'index'])->name('home');
