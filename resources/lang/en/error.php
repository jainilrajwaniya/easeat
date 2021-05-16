<?php

/*
  |--------------------------------------------------------------------------
  | Error messages
  |--------------------------------------------------------------------------
 */
return [
    'ERROR' => 'Error! Something went wrong',
    // Auth
    'INVALID_CREDENTIAL' => 'User credentials are incorrect',
    'USER_ACCOUNT_DELETED' => 'User account has been deleted',
    'USER_ACCOUNT_PENDING' => 'Please activate your account',
    'USER_INACTIVE' => 'User account is inactive',
    'PASSWORD_RESET_TOKEN_INVALID' => 'This password reset token is invalid.',
    'OLD_PASSWORD_NOT_MATCH' => 'Old password does not match.',
    'PASSWORD_RESET_TOKEN_EXPIRED' => 'Reset Password token has been expired.',
    'EMAIL_REGISTERED_FROM_SOCIAL_ACCOUNT' => 'The email is already registered with one of your social accounts. '
    . 'To continue, login using the social account.',
    'EMAIL_REGISTERED_FROM_NORMAL_ACCOUNT' => 'The email is already registered with normal account. '
    . 'To continue, login using your credentials.',
    'USER_NOT_LOGGEDIN' => 'User is not login',
    'INVALID_TOKEN' => 'Invalid Token',
    'USER_EMAIL_NOT_FOUND' => 'User not found with given email',
    'UNAUTHORIZED' => 'Unauthorized',
    
    // Region
    'COUNTY_ALREADY_EXISTS' => 'Governate already exists',
    'CITY_ALREADY_EXISTS' => 'Area already exists',
    'AREA_ALREADY_EXISTS' => 'Block/Street already exists',
    
    //kitchen-menu
    'VARIENT_NOT_FOUND' => 'Varient not found',
    'ADDON_NOT_FOUND' => 'Add on not found',
    
    
    /***************API*****************************/
    'USER_OR_GUEST_USER_NOT_FOUND' => 'User or guest user not found',
    'NO_KITCHEN_FOUND' => 'No kitchen found',
    'PHONE_NUMBER_MISSING' => 'Phone number missing',
    'SAME_PHONE_NUMBER_ENTERED' => 'This Phone number is same as your current one',
    'PHONE_NUMBER_ALREADY_TAKEN' => 'Phone number already taken',
    'EMAIL_ALREADY_TAKEN' => 'Email already taken',
    'OTP_MISMATCH' => 'OTP did not matched',
    'USER_CART_MISMATCH' => 'Cart does not belong to logged in user',
    'CART_ITEM_NOT_FOUND' => 'Cart item not found',
    'CART_IS_EMPTY' => 'Cart is empty',
    'ADDRESS_FOUND' => 'User address found',
    'ADDRESS_DID_NOT_BELONGS_TO_THIS_USER' => 'User address did not belongs to this user or not found',
    'KITCHEN_ITEM_MISMATCH' => 'Kitchen item does not belongs to this kitchen',
    'ITEM_VARIENT_MISMATCH' => 'Varient does not belongs to item or not found',
    'PROMOCODE_NOT_ACTIVE_OR_EXPIRED' => 'Promocode is not active or has expired',
    'PROMOCODE_NOT_ASSOC_WITH_THIS_CHEF' => 'Promocode is not applicable on this chef',
    'PROMOCODE_MINIMUM_ORDER_VALUE_NOT_REACHED' => 'Promocode not applied, minimum order value not reached',
    'PROMOCODE_CANT_BE_APPLIED_ON_COMPANY_DISCOUNT' => "Promocode can't be applied, since chef discount is already applied",
    'ERROR_OR_STRIPE_ERROR' => "Something went wrong! or problem with payment, please check your card details",
    'PAYMENT_TOKEN_NOT_FOUND' => "Payment gateway issue, token not found",
    'PAYMENT_URL_ISSUE' => "Payment gateway issue, redirect url not found",
    'ORDER_PAYMENT_ISSUE' => "Payment not done, please try again",
    'USER_ORDER_MISMATCH' => "Order does not belong to logged in user",
    'ITEM_OR_ADD_ON_INACTIVE' => "Your cart has items or addons which are not available right now, please clear the cart and add again",
    'ORDER_IS_EMPTY' => "Order is empty",
    'NOT_ENOUGH_BALANCE_IN_WALLET' => "Not enough balance in your wallet",
    'PAYMENT_ALREADY_DONE' => "Payment already done for this order",
    'CHEF_DOES_NOT_DELIVER_FOOD_AT_SELECTED_LOCATION' => "Chef does not deliver food at selected location",
    'CHEF_IS_NOT_AVAILABLE_TO_TAKE_ORDER_NOW' => "Chef is not available to take order now",
    'PLEASE_PASS_LAT_LONG' => 'Please pass lat long',
    'PACI_API_ISSUE' => 'Paci api issue',
    
    
    //Chef api
    'CHEF_ORDER_MISMATCH' => "Order does not belongs to logged in chef",
];
