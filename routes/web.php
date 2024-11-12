<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminBookingsController;
use App\Http\Controllers\AdminCountryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminPlaceController;
use App\Http\Controllers\AuthController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserPlaceController;
use App\Http\Controllers\UserUpgradeController;
use Illuminate\Contracts\Auth\UserProvider;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/userstory', function () {
    return Inertia::render('UserStoryPage');
});

Route::get('/user-story-detail', function () {
    return Inertia::render('UserStoryDetail');
});



Route::get('/testimonial', function () {
    return Inertia::render('TestimonialPage');
});

Route::get('/contactus', function () {
    return Inertia::render('ContactUsPage');
});

Route::get('/user/datePlaces',function(){
    return Inertia::render('User/UserDatePlaces');
});

Route::middleware(['auth'])->controller(UserUpgradeController::class)->group(function(){
    Route::get('/user/upgrade-account', 'showUserData');
    Route::post('/user/upgrade-account','storePayment')->name('user.upgrade');
});

Route::controller(UserPlaceController::class)->group(function(){
    Route::get('/user/datePlaces','showRestaurants')->name('user.datePlaces');
    Route::post('/user/datePlaces','bookDate')->name('user.bookDate');
});

Route::controller(UserAuthController::class)->group(function(){
    Route::post('/register','UserRegister');
    Route::post('/login','UserLogin')->name('login');
    Route::post('/user/logout','UserLogout');
});

Route::controller(ForgotPasswordController::class)->group(function(){
    Route::post('/user/forgotPassword','forgotPassword')->name('home');
});

Route::controller(AdminAuthController::class)->group(function(){
    Route::get('/admin/login','showAdminLogin');
    Route::get('/admin/register','showAdminRegister');
    Route::post('/admin/register','AdminRegister');
    Route::post('/admin/login','AdminLogin');
    Route::post('/admin/logout','AdminLogout');
});


Route::controller(AdminDashboardController::class)->group(function(){
    Route::get('/admin/dashboard','AdminDashboardCount')->name('admin.dashboard');
    Route::get('/admin/usersTable','UserTable')->name('admin.userstable');
});
Route::controller(HomeController::class)->group(function(){
    Route::get('/','showProfiles');
    Route::get('/profile', 'profiles');
});

Route::middleware(['auth'])->controller(ProfileController::class)->group(function () {
    Route::get('/user/userProfile', 'myProfile')->name('user.profile');
    Route::post('/user/userProfile', 'updateProfile')->name('profile.update');
    Route::post('/user/userProfile/uploadProfilePicture','uploadProfilePicture')->name('profile.uploadProfilePicture');
    Route::post('/user/userProfile/updateHobbies','updateHobbies')->name('profile.updateHobbies');
    Route::post('/user/userProfile/updatePreference','updatePreference')->name('profile.updatePreference');
    Route::post('/user/userProfile/updateAboutMe','updateAppearance')->name('profile.updateAboutMe');
    Route::get('/user/loginProfile','getUserProfilePicture')->name('user.loginProfile');
});
Route::middleware(['auth'])->controller(MessageController::class)->group(function(){
    Route::post('/user/messages','sendMessage')->name('message.send');
    Route::get('/user/messages','chatList')->name('chat.list');
    Route::get('/user/messages/start','startNewChat')->name('message.start');
    Route::get('/user/viewProfile/{userId}','viewProfile')->name('viewProfile.show');
});

Route::controller(UserDashboardController::class)->group(function(){
    Route::get('/user/dashboard','potentialProfiles')->name('user.dashboard')->middleware('auth');
});

Route::controller(MatchingController::class)->group(function(){
    Route::get('/user/matching/{userId}','findMatches')->name('user.matches');
});

Route::controller(AdminCountryController::class)->group(function(){
    Route::get('/admin/countryTable','showCountry');
    Route::post('/admin/countryTable','createCountry');
    Route::delete('/admin/countryTable/{country}','deleteCountry')->name('admin.countryTable');
});

Route::controller(AdminPlaceController::class)->group(function(){
    Route::get('/admin/datePlaces','showRestaurants')->name('admin.restaurants');
    Route::get('/admin/datePlaces/detailPlace/{restaurantId}','showEachRestaurants')->name('admin.viewDetail');
    Route::post('/admin/datePlaces/detailPlace/{restaurantId}','updateRestaurant')->name('admin.updateRestaurant');
    Route::delete('/admin/datePlaces/detailPlace/{restaurantId}','deleteRestaurant')->name('admin.deleteRestaurant');
    Route::get('/admin/datePlaces/create','goToCreate')->name('admin.createRoute');
    Route::post('/admin/datePlaces/create','createNewRestaurant')->name('admin.newRestaurant');
});

Route::controller(AdminPaymentController::class)->group(function(){
    Route::get('/admin/paymentLists','showPaymentLists');
    Route::post('/admin/paymentLists/{payment_id}/{action}','updatePaymentStatus');
});

Route::controller(AdminBookingsController::class)->group(function(){
    Route::get('/admin/bookings','viewAllDates');   
});
