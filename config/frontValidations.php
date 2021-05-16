<?php

return [
    "SPLASH" => [
        'email' => 'email',
        'device_token' => 'required'
    ],
    "SOCIAL_LOGIN" => [
        'email' => 'required|email',
        'device_token' => 'required',
        'fcm_token' => 'required', 
    ],
    /*device_token validation not included in social register because we will
    validate device token from socal login validation*/
    "SOCIAL_REGISTER" => [
//        'phone_number' => 'required|unique:users',
        'email' => 'required|unique:users|email',
        'device_type' => 'required|in:Iphone,Android',
        'signup_from' => 'required|in:Facebook,Google',
        'fcm_token' => 'required', 
    ],
    "LOGIN" => [
        //'phone_number' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8',
        'device_token' => 'required',
        'fcm_token' => 'required', 
    ],
    "GUESTLOGIN" => [
        'fcm_token' => 'required', 
        'device_token' => 'required',
        'device_type' => 'in:Iphone,Android'
    ],
    "REGISTER" => [ 
//        'phone_number' => 'required|unique:users', 
        'device_token' => 'required', 
        'fcm_token' => 'required', 
        'device_type' => 'required|in:Iphone,Android', 
        'email' => 'required|unique:users|email', 
        'password' => 'required|min:8', 
        'confirm_password' => 'required|same:password', 
    ],
    "SAVE_USER_ADDRESS" => [
        'name' => 'required|min:4|max:50', 
//        'address' => 'required|min:4|max:100',
//        'latitude' => 'required',
//        'longitude' => 'required',
//        'gov_en' => 'required',
//        'area_en' => 'required',
        'block' => 'required',
//        'street' => 'required',
    ],
    "CHANGE_PASSWORD" => [
        'old_password' => 'required|min:8', 
        'new_password' => 'required|min:8', 
        'confirm_password' => 'required|same:new_password', 
    ],
    "CREATE_ORDER" => [
        'cart_id' => 'required|integer|exists:cart,id',
        'delivery_type' => 'required|in:HomeDelivery,Pickup',
        'contact_person_no' => 'required|numeric',
        'delivery_latitude' => 'required',
        'delivery_longitude' => 'required',
        'delivery_address' => 'required',
    ],
    /*CHEF VALIDATIONS*/
    "CHEF_LOGIN" => [
        'email' => 'required|email|exists:chefs,email',
        'password' => 'required|min:8',
        'device_token' => 'required',
        'device_type' => 'in:Iphone,Android'
    ],
    "CHEF_ORDER_STATUS_CHANGE" => [
        'order_id' => "required|exists:orders,id",
        'status' => 'required|in:Cooking,Ready,OnTheWay,Completed,Chef_Cancelled'
    ]
];
