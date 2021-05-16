<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/user/login', 'UserController@login');
Route::post('/user/sociallogin', 'UserController@socialLogin');
Route::post('/logout', 'LoginController@logout');
Route::post('/user/register', 'UserController@register');
//Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
Route::post('/user/password/email', 'UserController@sendResetLinkEmail');
//GUEST USER LOGIN
Route::post('/guest_user_login', 'GuestUserController@guestUserLogin');
Route::post('/testFCM', 'HomeController@testFCM');// test fcm

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//   
//});

//Route::group(['middleware' => ['authuser'], 'prefix' => 'shipment/api', 'namespace' => 'Modules\Shipment\Http\Controllers'], function () {
Route::group(['middleware' => ['auth:api']], function () {
    //USER PROFILE
    Route::post('/user/detail', 'UserController@detail'); 
    Route::post('/user/edit_profile', 'UserController@editProfile'); 
    Route::post('/user/change_password', 'UserController@changePassword'); 
    
    //PHONE NUMNER
    Route::post('/verify_phone_number', 'PhoneNumberController@verifyPhoneNumber'); 
    Route::post('/add_phone_number', 'PhoneNumberController@addPhoneNumber'); 

    //ADDRESS
    Route::post('/user_address/get_address_list', 'UseraddressController@getAddressList');
    Route::post('/user_address/get_address', 'UseraddressController@getAddress');
    Route::post('/user_address/save_address', 'UseraddressController@saveAddress');
    Route::post('/user_address/remove_address', 'UseraddressController@removeAddress');
    Route::post('/user_address/get_formated_address', 'UseraddressController@getFormatedAddress');
    
    //FAVOURITE
    Route::post('/user/mark_kitchen_as_favourite', 'UserController@markKitchenAsFavourite');
    Route::post('/user/get_favourite_kitchens', 'UserController@getFavouriteKitchens');
    
    //RATINGS
    Route::post('/ratings/save_kitchen_rating', 'RatingController@saveKitchenRating');
    
    //CART
    Route::post('/add_item_to_cart', 'CartController@addItemToCart');
    Route::post('/repeat_item_in_cart', 'CartController@repeatItemInCart');
    Route::post('/remove_item_from_cart', 'CartController@removeItemFromCart');
    Route::post('/clear_cart', 'CartController@clearCart');
    Route::post('/cart_detail', 'CartController@cartDetail');
    
    //ORDER
    Route::post('/create_order', 'OrderController@createOrder');
    Route::post('/order_detail', 'OrderController@getOrderDetails');
    Route::post('/order_list', 'OrderController@getOrderList');
    
    //PAYMENT
    Route::post('/pay_from_card', 'PaymentController@payFromCard');
    Route::post('/pay_from_wallet', 'PaymentController@payFromWallet');
    Route::post('/pay_from_myfatoorah', 'PaymentController@pay');
});

Route::post('/get_paci_data', 'PaciController@getPaciData');

//open apis
Route::post('/chefs/get_all', 'HomeController@getChefs');
Route::post('/getSearchFilters', 'HomeController@getSearchFilters');

//PROMOCODES
Route::post('/promocodes/get_all_active_promocodes', 'PromocodeController@getActivePromoCodes');
Route::post('/promocodes/check_and_apply', 'PromocodeController@checkAndApply');
Route::post('/promocodes/remove_promocode', 'PromocodeController@removePromocode');

//KITCHEN
Route::post('/kitchens/get_details', 'KitchenController@getKitchenDetail');
Route::post('/kitchen_menu/get_items', 'KitchenMenuController@getKitchenItems');
Route::post('/kitchen_item/get_varients_and_add_ons', 'KitchenMenuController@getAddOnsOnKitchenItemId');

//RATINGS
Route::post('/ratings/get_kitchen_rating', 'RatingController@getKitchenRatings');

//messages , terma and condition
Route::get('/get_message', 'SettingController@getMessage');



/****************Merchant/Chef app Apis******************/
Route::post('/chef-app/login', 'ChefLoginController@login');
Route::post('/chef-app/get_orders', 'ChefOrderController@getOrders');
Route::post('/chef-app/get_order_detail', 'ChefOrderController@getOrderDetail');
Route::post('/chef-app/change_order_status', 'ChefOrderController@changeOrderStatus');
Route::post('/chef-app/change_chef_status', 'ChefOrderController@changeChefStatus');
Route::post('/chef-app/get_chef_status', 'ChefOrderController@getChefStatus');
Route::post('/chef-app/get_chef_items', 'ChefOrderController@getKitchenItems');
Route::post('/chef-app/change_item_status', 'ChefOrderController@changeItemStatus');



