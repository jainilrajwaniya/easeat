<?php
//dashboard
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('dashboard');

//Route::get('/home', function () {
//    $users[] = Auth::user();
//    $users[] = Auth::guard()->user();
//    $users[] = Auth::guard('chef')->user();
//
//    //dd($users);
//
//    return view('chef.home');
//})->name('home');

//Setting management
Route::group(['prefix' => 'settings'], function () {
    Route::get('profile_form', 'SettingController@editProfile')->name('profile_edit_form');
    Route::post('profile/save', 'SettingController@saveProfile')->name('profile_save');
    Route::post('change_password/save', 'SettingController@passwordStore')->name('store_password');
    Route::get('change_password_form', 'SettingController@passwordIndex')->name('change_password');
    Route::post('ajax_mark_kitchen_open_close', 'SettingController@markKitchenOpenClose')->name('markKitchenOpenClose');
});

//Kitchen Menu management
Route::group(['prefix' => 'kitchenmenu'], function () {
    Route::get('listing', 'KitchenMenuController@index')->name('kitchenmenu_listing');
    Route::get('ajax_listing', 'KitchenMenuController@ajaxGetKitchenMenuList')->name('kitchenmenu_ajax_listing');
    Route::get('show_edit_form/{id?}', 'KitchenMenuController@editKitchenMenu')->name('kitchenmenu_show_edit_form');
    Route::post('save', 'KitchenMenuController@saveKitchenMenu')->name('kitchenmenu_save');
    Route::get('ajax_change_status/{id?}', 'KitchenMenuController@changeKitchenMenuStatus')->name('change_kitchenmenu_status');
    
    Route::get('addon/listing/{id?}', 'KitchenMenuController@indexAddon')->name('kitchenmenu_addon_listing');
    Route::get('addon/ajax_listing/{id?}', 'KitchenMenuController@ajaxGetKitchenMenuAddonList')->name('kitchenmenu_addon_ajax_listing');
    Route::get('addon/show_edit_form/{kitchenItemId?}/{id?}', 'KitchenMenuController@editKitchenAddOnMenu')->name('kitchen_add_on_show_edit_form');
    Route::post('saveAddon', 'KitchenMenuController@saveKitchenAddOn')->name('kitchenaddon_save');
    Route::get('addon/ajax_change_addon_cat_status/{id?}', 'KitchenMenuController@ajaxChangeAddonCatStatus')->name('ajax_change_addon_cat_status');
    Route::get('ajax_category_listing', 'KitchenMenuController@ajaxGetCategoryList')->name('kitchenmenu_ajax_category_listing');
    
    Route::get('ajax_delete_addon_cat/{id?}', 'KitchenMenuController@ajaxDeleteAddonCat')->name('kitchenmenu_ajax_delete_addon_cat');
    Route::get('ajax_delete_addon/{id?}', 'KitchenMenuController@ajaxDeleteAddon')->name('kitchenmenu_ajax_delete_addon');
    Route::get('ajax_delete_varient/{id?}', 'KitchenMenuController@ajaxDeleteVarient')->name('kitchenmenu_ajax_delete_varient');
    Route::get('ajax_edit_item_order', 'KitchenMenuController@ajaxEditItemOrder')->name('kitchenmenu_ajax_edit_item_order');
    Route::get('ajax_edit_addon_cat_order', 'KitchenMenuController@ajaxEditAddonCatOrder')->name('kitchenmenu_ajax_edit_addon_cat_order');
    
    Route::get('bulkupload', 'KitchenMenuController@bulkUpload')->name('kitchenmenu_bulk_upload');
    Route::post('bulkupload/save', 'KitchenMenuController@bulkUploadSave')->name('kitchenmenu_bulk_upload_save');
    Route::get('download_kitchen_menu_excel', 'KitchenMenuController@downloadKitchenMenuExcel');
    
});

//Kitchen Timing management
Route::group(['prefix' => 'kitchentiming'], function () {
    Route::get('listing', 'KitchenTimingController@index')->name('kitchentiming_listing');
    Route::get('ajax_listing', 'KitchenTimingController@ajaxGetKitchenTimingList')->name('kitchentiming_ajax_listing');
    Route::get('show_edit_form/{id?}', 'KitchenTimingController@editKitchenTiming')->name('kitchentiming_show_edit_form');
    Route::post('save', 'KitchenTimingController@saveKitchenTiming')->name('kitchentiming_save');
    Route::post('change_category_status/{id?}', 'KitchenTimingController@changeKitchenTimingStatus')->name('change_kitchentiming_status');
});

//Rating management
Route::group(['prefix' => 'rating'], function () {
    Route::get('listing', 'RatingController@index')->name('rating_listing');
    Route::get('ajax_listing', 'RatingController@ajaxGetRatingList')->name('rating_ajax_listing');
    Route::get('ajax_change_status/{id}', 'RatingController@changeRatingStatus')->name('ajax_change_status');
    Route::get('ajax_get_rating_data/{id}', 'RatingController@getRatingData')->name('ajax_get_rating_data');
});

//Orders management
Route::group(['prefix' => 'orders'], function () {
    Route::get('listing', 'OrderController@index')->name('orders_listing');
    Route::get('ajax_listing', 'OrderController@ajaxGetOrdersList')->name('orders_ajax_listing');
    Route::post('ajax_update_order_status', 'OrderController@ajaxUpdateOrderStatus')->name('ajax_update_order_status');
    Route::post('ajax_get_order_detail', 'OrderController@ajaxGetOrderDetail')->name('ajax_get_order_detail');
    Route::get('detail/{id}', 'OrderController@orderDetail')->name('orders_detail');
    Route::get('ajax_get_chef_placed_order', 'OrderController@ajaxGetChefPlacedOrder')->name('ajax_get_chef_placed_order');
});

//group management
Route::group(['prefix' => 'group'], function () {
    Route::get('listing', 'GroupController@index')->name('group_listing');
    Route::get('ajax_listing', 'GroupController@ajaxGetGroupList')->name('group_ajax_listing');
    Route::get('ajax_edit_group', 'GroupController@ajaxEditGroup')->name('ajax_edit_group');
    Route::post('ajax_update_order_status', 'GroupController@ajaxUpdateOrderStatus')->name('ajax_update_order_status');
    Route::get('ajax_edit_group_order', 'GroupController@ajaxEditGroupOrder')->name('ajax_update_group_order');
});
