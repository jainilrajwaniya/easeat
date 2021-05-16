<?php
//dashboard
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('dashboard');


//subadmin management
Route::group(['prefix' => 'subadmin'], function () {
    Route::get('listing', 'SubadminController@index')->name('subadmin_listing');
    Route::get('ajax_listing', 'SubadminController@ajaxGetAdminList')->name('subadmin_ajax_listing');
    Route::get('show_edit_form/{id?}', 'SubadminController@editSubAdmin')->name('subadmin_show_edit_form');
    Route::post('save', 'SubadminController@saveSubAdmin')->name('subadmin_save');
});

//user management
Route::group(['prefix' => 'user'], function () {
    Route::get('listing', 'UserController@index')->name('user_listing');
    Route::get('ajax_listing', 'UserController@ajaxGetUserList')->name('user_ajax_listing');
});

//Rating management
Route::group(['prefix' => 'rating'], function () {
    Route::get('listing', 'RatingController@index')->name('rating_listing');
    Route::get('ajax_listing', 'RatingController@ajaxGetRatingList')->name('rating_ajax_listing');
    Route::get('ajax_change_status/{id}', 'RatingController@changeRatingStatus')->name('ajax_change_status');
    Route::get('ajax_get_rating_data/{id}', 'RatingController@getRatingData')->name('ajax_get_rating_data');
});

//Category management
Route::group(['prefix' => 'category'], function () {
    Route::get('listing', 'CategoryController@index')->name('category_listing');
    Route::get('show_edit_form/{id?}', 'CategoryController@editCategory')->name('category_show_edit_form');
    Route::post('save', 'CategoryController@saveCategory')->name('category_save');
    Route::post('change_category_status/{id?}', 'CategoryController@changeCategoryStatus')->name('change_category_status');
});

//Cuisine management
Route::group(['prefix' => 'cuisine'], function () {
    Route::get('listing', 'CuisineController@index')->name('cuisine_listing');
    Route::get('show_edit_form/{id?}', 'CuisineController@editCuisine')->name('cuisine_show_edit_form');
    Route::post('save', 'CuisineController@saveCuisine')->name('cuisine_save');
    Route::post('change_cuisine_status/{id?}', 'CuisineController@changeCuisineStatus')->name('change_cuisine_status');
});

//Region management
Route::group(['prefix' => 'region'], function () {
    Route::get('listing', 'RegionController@index')->name('region_listing');
    Route::get('ajax_get_counties', 'RegionController@ajaxGetCounties')->name('ajax_get_counties');
    Route::get('ajax_get_cities', 'RegionController@ajaxGetCities')->name('ajax_get_cities');
    Route::get('ajax_get_areas', 'RegionController@ajaxGetAreas')->name('ajax_get_areas');
    Route::post('ajax_update_county/{id?}', 'RegionController@ajaxUpdateCounty')->name('ajax_update_county');
    Route::post('ajax_update_city/{id?}', 'RegionController@ajaxUpdateCity')->name('ajax_update_city');
    Route::post('ajax_update_area/{id?}', 'RegionController@ajaxUpdateArea')->name('ajax_update_area');
    Route::post('ajax_delete/{id?}', 'RegionController@ajaxDelete')->name('ajax_delete');

    Route::get('get-state-list','RegionController@getStateList');
    Route::get('get-city-list','RegionController@getCityList');
    Route::get('get-area-list','RegionController@getAreaList');
});


//Activity Log management
Route::group(['prefix' => 'log-activity'], function () {
    Route::get('listing', 'LogActivityController@index')->name('activity_log_listing');
    Route::get('ajax_listing', 'LogActivityController@ajaxGetLogActivityList')->name('activity_log_ajax_listing');
});

//Chef management
Route::group(['prefix' => 'chef'], function () {
    Route::get('listing', 'ChefController@index')->name('chef_listing');
    Route::get('ajax_listing', 'ChefController@ajaxGetChefList')->name('chef_ajax_listing');
    Route::get('show_edit_form/{id?}', 'ChefController@editChef')->name('chef_show_edit_form');
    Route::post('save', 'ChefController@saveChef')->name('chef_save');
    Route::get('kitchen_edit_form/{id?}', 'ChefController@editKitchen')->name('kitchen_edit_form');
    Route::post('kitchen/save', 'ChefController@saveKitchen')->name('kitchen_save');
    Route::get('kitchen_image_form/{id?}', 'ChefController@editKitchenImage')->name('kitchen_image_form');
    Route::post('kitchen_images/save', 'ChefController@saveKitchenImage')->name('kitchen_image_save');
    Route::delete('kitchen_images/delete/{id?}', 'ChefController@deleteImage')->name('ajax_delete');
    Route::get('ajax_change_status/{id}', 'ChefController@changeChefStatus')->name('ajax_change_status');
});

//Prmocode management
Route::group(['prefix' => 'promocode'], function () {
    Route::get('listing', 'PromoCodeController@index')->name('promocode_listing');
    Route::get('ajax_listing', 'PromoCodeController@ajaxGetPromocodeList')->name('promocode_ajax_listing');
    Route::get('show_edit_form/{id?}', 'PromoCodeController@editPromocode')->name('promocode_show_edit_form');
    Route::post('save', 'PromoCodeController@savePromocode')->name('promocode_save');
    Route::get('ajax_change_status/{id}', 'PromoCodeController@changePromocodeStatus')->name('ajax_change_status');
    Route::get('kitchen/listing', 'PromoCodeController@indexPromoKitchen')->name('promocode_assign_show_edit_form');
    Route::post('kitchen/save', 'PromoCodeController@savePromocodeKitchen')->name('promocode_assign_save');
    Route::get('get-kitchen-list', 'PromoCodeController@getKitchenList')->name('promocode_ajax_form');
});

//Setting management
Route::group(['prefix' => 'settings'], function () {
    Route::get('profile_form', 'SettingController@editProfile')->name('profile_edit_form');
    Route::post('profile/save', 'SettingController@saveProfile')->name('profile_save');
    Route::post('change_password/save', 'SettingController@passwordStore')->name('store_password');
    Route::get('change_password_form', 'SettingController@passwordIndex')->name('change_password');
});

// Wallet management
Route::group(['prefix' => 'wallet'], function () {
    Route::get('listing', 'WalletController@index')->name('wallet_listing');
    Route::get('ajax_listing', 'WalletController@ajaxGetWalletList')->name('wallet_ajax_listing');
    Route::get('show_edit_form/{id?}', 'WalletController@editWallet')->name('wallet_show_edit_form');
    Route::post('save', 'WalletController@saveWallet')->name('wallet_save');
});

//Orders management
Route::group(['prefix' => 'orders'], function () {
    Route::get('listing', 'OrderController@index')->name('orders_listing');
    Route::get('ajax_listing', 'OrderController@ajaxGetOrdersList')->name('orders_ajax_listing');
    Route::get('detail/{id}', 'OrderController@orderDetail')->name('orders_detail');
});

//Route::get('/home', function () {
//    $users[] = Auth::user();
//    $users[] = Auth::guard()->user();
//    $users[] = Auth::guard('admin')->user();
//
//    //dd($users);
//
//    return view('admin.home');
//})->name('home');

