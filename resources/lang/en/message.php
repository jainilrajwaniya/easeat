<?php

/*
  |--------------------------------------------------------------------------
  | Flash messages: For success, error, info etc
  |--------------------------------------------------------------------------
 */
return [
    'SUCCESS' => 'Success',
    'NO_RECORD_FOUND' => 'No record found',
    'ORDER_NOT_FOUND' => 'Order not found',
    'NO_FAQ_FOUND' => 'Faq(s) not found',
    'USER_LOGGEDIN' => 'User is loggedin',
    'GUESTUSER_LOGGEDIN' => 'Guest user is loggedin',
    'ADMIN_USER_LOGGEDIN' => 'Admin user is loggedin',
    'PASSWORD_RESET' => 'Your password has been reset!',
    'PASSWORD_RESET_LINK_SENT' => 'We have e-mailed your password reset link!',
    'PASSWORD_RESET_TOKEN_INVALID' => 'This password reset token is invalid.',
    'OLD_PASSWORD_NOT_MATCH' => 'Old password does not match.',
    'PASSWORD_CHANGED' => 'Your password has been changed.',
    'PASSWORD_TOKEN_VALID' => 'This is Valid Reset Password token.',
    'USER_STATUS_ACTIVE' => 'User status has been changed to Active successfully',
    'USER_STATUS_INACTIVE' => 'User status has been changed to Inactive successfully',
    'USER_REGISTERED' => 'User registered successfully',
    'PROFILE_UPDATED' > 'User Profile is updated successfully',
    'ACCOUNT_SUCCESSFULLY_ACTIVATED' => 'Account activated successfully',
    'USER_EMAIL_NOT_FOUND' => 'User not found with given email',
    'PROFILE_UPDATED' => 'Profile updated',
    
    // Sub admin
    'SUB_ADMIN_CREATED' => 'Sub admin user has been created successfully',
    'SUB_ADMIN_UPDATED' => 'Sub admin user has been updated successfully',
    'SUB_ADMIN_DELETED' => 'Sub admin user has been deleted successfully',
    'USER_PROFILE_UPDATED' => 'Profile updated successfully',
    
    
    // Sub admin
    'RATING_STATUS_CHANGED' => 'Rating status has been changed successfully',
    'PROMOCODE_STATUS_CHANGED' => 'Promo code status has been changed successfully',
    
    // Sub admin
    'CHEF_STATUS_CHANGED' => 'Chef status has been changed successfully',
    'KITCHEN_MENU_STATUS_CHANGED' => 'Kitchen Menu status has been changed successfully',
    'ADDON_CAT_STATUS_CHANGED' => 'Add on category status has been changed successfully',
    
    //region
    'COUNTY_UPDATED' => 'Governate has been updated successfully',
    'COUNTY_CREATED' => 'Governate has been created successfully',
    'CITY_UPDATED' => 'Area has been updated successfully',
    'CITY_CREATED' => 'Area has been created successfully',
    'AREA_UPDATED' => 'Block/Street has been updated successfully',
    'AREA_CREATED' => 'Block/Street has been created successfully',
    'AREA_DELETED' => 'Block/Street has been deleted successfully',
    'CITY_DELETED' => 'Area has been deleted successfully',
    'STATE_DELETED' => 'Governate has been deleted successfully',
    'STATE_IN_USE' => 'Cannot delete Governate, first delete its areas',
    'CITY_IN_USE' => 'Cannot delete Area, first delete its Block/Street',
    
    // FAQ
    'FAQ_CREATED' => 'Faq has been created successfully',
    'FAQ_UPDATED' => 'Faq has been updated successfully',
    'FAQ_DELETED' => 'Faq has been deleted successfully',
    'FAQ_STATUS_ACTIVE' => 'Faq status has been changed to Active successfully',
    'FAQ_STATUS_INACTIVE' => 'Faq status has been changed to Inactive successfully',
    
    //Kitchen Menu
    'VARIENT_DELETED' => 'Varient has been deleted successfully',
    'ADDON_DELETED' => 'Add on has been deleted successfully',
    
    //API:user favourite
    'CHEF_MARKED_FAVOURITE' => 'Chef marked as favourite successfully',
    'CHEF_ALREADY_MARKED_FAVOURITE' => 'Chef already marked as favourite',
    'CHEF_REMOVED_FAVOURITE' => 'Chef removed from favourite successfully',
    
    //ratings
    'RATING_SAVED_SUCCESSFULLY' => 'Rating saved successfully',
    
    //user address
    'USER_ADDRESS_SAVED_SUCCESSFULLY' => 'User address saved successfully',
    'ADDRESS_FOUND' => 'User address found',
    'USER_ADDRESS_EDITED_SUCCESSFULLY' => 'User address edited successfully',
    'ADDRESS_DID_NOT_BELONGS_TO_THIS_USER' => 'User address did not belongs to this user or not found',
    'ADDRESS_DELETED_SUCCESSFULLY' => 'User address deleted successfully',
    
    //cart
    'ITEM_ADDED_IN_CART' => 'Item added in cart successfully',
    'ITEM_DELETED_FROM_CART' => 'Item removed from cart successfully',
    'ITEM_NOT_FOUND' => 'Item not found in cart',
    'ADD_ON_ADDED_IN_CART' => 'Add on added in cart successfully',
    'USER_CART_MISMATCH' => 'Cart does not belong to logged in user',
    'CART_CLEARED' => 'Cart cleared',
    
    //phone number 
    'OTP_SENT' => 'OTP sent',
    'PHONE_NUMBER_ADDED' => 'Phone number added',
    
    //promo code
    'PROMOCODE_DISCOUNT_APPLIED' => 'Promocode discount applied',
    'PROMOCODE_REMOVED' => 'Promocode removed',
    
    //Order
    'ORDER_CREATED' => 'Order created',
    'ORDER_PAYMENT_DONE' => 'Order payment done',
    'PAYMENT_URL' => 'Payment url',
    'ORDER_MARKED_AS_COOKING' => "Order status changed to cooking",
    'ORDER_MARKED_AS_READY' => "Order status changed to ready",
    'ORDER_MARKED_AS_ONTHEWAY' => "Order status changed to on the way",
    'ORDER_MARKED_AS_COMPLETED' => "Order status changed to completed",
    'ORDER_FOUND' => "Order found",
    'ORDER_LIST' => "Order list",
    
    //group
    'GROUP_CREATED' => 'Group created',
    'GROUP_EDITED' => 'Group Edited',
    'GROUP_ORDER_CHANGED' => 'Group Order Changed',
    'ITEM_ORDER_CHANGED' => 'Item Order Changed',
    
    //CHEF
    'ORDER_ALREADY_MARKED_AS_REQUIRED' => 'Order already marked as required',
    'CHEF_MARKED_AS_ONLINE' => 'Chef marked as online',
    'CHEF_MARKED_AS_OFFLINE' => 'Chef marked as offline',
    'CHEF_IS_ONLINE' => 'Chef is online',
    'CHEF_IS_OFFLINE' => 'Chef is offline',
    
    //DELIVRY TYPE
    'HomeDelivery' => 'Home Delivery',
    'Pickup' => 'Pick up',
    'PreOrder' => 'Pre Order',
    
    //ORDER STATUS
    'Pending' => 'Pending',
    'Placed' => 'Placed',
    'Cooking' => 'Cooking',
    'Ready' => 'Ready',
    'Completed' => 'Completed',
    'Customer_Cancelled' => 'Customer Cancelled',
    'Chef_Cancelled' => 'Chef Cancelled',
    'PaymentIssue' => 'Payment Issue',
    'Waiting_Pickup' => 'Waiting Pickup',
    'Waiting_Home_Delivery' => 'Waiting Home Delivery',
    'OnTheWay' => 'On The Way',
    
    
    /*******************/
    
    // CONTENT PAGE
    'CONTENT_PAGE_CREATED' => 'Content page has been created successfully',
    'CONTENT_PAGE_UPDATED' => 'Content page has been updated successfully',
    'CONTENT_PAGE_DELETED' => 'Content page has been deleted successfully',
    'CONTENT_PAGE_STATUS_ACTIVE' => 'Content page status has been changed to Active successfully',
    'CONTENT_PAGE_STATUS_INACTIVE' => 'Content page status has been changed to Inactive successfully',
    
    //Permission Manager
    'ROLE_UPDATED' => 'Role updated successfully',
    'ROLE_CREATED' => 'Role created successfully',
    'ROLE_DELETED' => 'Role deleted successfully',
    'ROLE_STATUS_ACTIVE' => 'Role status has been changed to Active successfully',
    'ROLE_STATUS_INACTIVE' => 'Role status has been changed to Inactive successfully',
    'ROLE_LIST_SUCCESS' => 'Role Listing successfully',
  
    'PERMISSION_CREATED' => 'Permission has been created successfully',
    'PERMISSION_UPDATED' => 'Permission has been updated successfully',
    'PERMISSION_DELETED' => 'Permission has been deleted successfully',
    'PERMISSION_ASSIGNED' => 'Permission has been assigned successfully',
    'PERMISSION_LIST_SUCCESS' => 'Permission has been listed successfully',
    
    // Email Template
    'EMAIL_TEMPLATE_CREATED' => 'Email template has been created successfully',
    'EMAIL_TEMPLATE_UPDATED' => 'Email template has been updated successfully',
    'EMAIL_TEMPLATE_DELETED' => 'Email template has been deleted successfully',
    'EMAIL_TEMPLATE_STATUS_ACTIVE' => 'Email template status has been changed to Active successfully',
    'EMAIL_TEMPLATE_STATUS_INACTIVE' => 'Email template status has been changed to Inactive successfully',

    // Sub admin
    'USER_CREATED' => 'User has been created successfully',
    'USER_UPDATED' => 'User has been updated successfully',
    'USER_DELETED' => 'User has been deleted successfully',
    'USER_PROFILE_DELETED' => 'User has been deleted profile successfully',
    
    //configuration
    'CONFIG_SAVED' => 'Configuration saved successfully',
    'CONFIG_NOT_FOUND' => 'Configuration data not found',
    'IMAGE_DELETED' => 'Image has been deleted successfully',
    
    // Category
    'CATEGORY_CREATED' => 'Category has been created successfully',
    'CATEGORY_UPDATED' => 'Category has been updated successfully',
    'CATEGORY_DELETED' => 'Category has been deleted successfully',
    'CATEGORY_STATUS_ACTIVE' => 'Category status has been changed to Active successfully',
    'CATEGORY_STATUS_INACTIVE' => 'Category status has been changed to Inactive successfully',
    'CATEGORY_LIST_SUCCESS' => 'Category has been listed successfully',
    
    // Location
    'LOCATION_CREATED' => 'Location has been created successfully',
    'LOCATION_UPDATED' => 'Location has been updated successfully',
    'LOCATION_DELETED' => 'Location has been deleted successfully',
    'LOCATION_STATUS_CHANGE' => ' Location Status has been change successfully',
    
    // Interest
    'INTEREST_CREATED' => 'Interest has been created successfully',
    'INTEREST_UPDATED' => 'Interest has been updated successfully',
    'INTEREST_DELETED' => 'Interest has been deleted successfully',
    'INTEREST_STATUS_CHANGE' => 'Interest Status has been change successfully',

    // Goodies
    'GOODIES_CREATED' => 'Goodies has been created successfully',
    'GOODIES_UPDATED' => 'Goodies has been updated successfully',
    'GOODIES_DELETED' => 'Goodies has been deleted successfully',
    'GOODIES_STATUS_CHANGE' => 'Goodies Status has been change successfully',
    
    // Activity
    'ACTIVITY_CREATED' => 'Activity has been created successfully',
    'ACTIVITY_UPDATED' => 'Activity has been updated successfully',
    'ACTIVITY_DELETED' => 'Activity has been deleted successfully',
    'ACTIVITY_STATUS_CHANGE' => 'Activity Status has been change successfully',
    
    // Report Abuse
    'REPORT_ABUSE_DELETED' => 'Report has been deleted successfully',
    'REPORT_ABUSE_STATUS_CHANGE' => 'Report Status has been change successfully',
    'REPORT_CREATED' => 'Report has been created successfully',
    'REPORT_NOT_CREATED' => 'Report not created value not found',
    
    // Survey
    'SURVEY_CREATED' => 'Survey has been created successfully',
    'SURVEY_UPDATED' => 'Survey has been updated successfully',
    'SURVEY_STATUS_CHANGE'=> 'Survey status has been change successfully',
    'SURVEY_STATE_CHANGE'=> 'Survey state has been change successfully',
    'SURVEY_DELETED' => 'Survey has been deleted successfully',

    // Survey Question
    'SURVEY_QUESTION_CREATED'=> 'Survey question has been created successfully',
    'SURVEY_QUESTION_UPDATED'=>'Survey question has been updated successfully',
    'QUESTION_ANS_TYPE_CHANGE'=>'Survey question answer type has been change successfully',
    'SURVEY_QUESTION_DELETED'=>'Survey question has been deleted successfully',
    
    // Survey Question Answer
    'SURVEY_QUESTION_ANSWER_CREATED'=> 'Survey answer has been created successfully',
    'SURVEY_QUESTION_ANSWER_UPDATED'=>'Survey answer has been updated successfully',
    'SURVEY_QUESTION_ANSWER_DELETED'=>'Survey answer has been deleted successfully',
    
    //Deal
    "DEAL_CREATED"=>'Deal has been created successfully',
    "DEAL_UPDATED"=>'Deal has been updated successfully',
    "DEAL_STATUS_CHANGE"=>'Deal status has been change successfully',
    "DEAL_STATE_CHANGE"=>'Deal state has been changed successfully',
    "DEAL_DELETED"=>'Deal has been deleted successfully',
    'DEAL_BOOKMARK_ADDED'=>'Deal has been bookmarked successfully.',
    'DEAL_BOOKMARK_REMOVED'=>'Deal has been removed from bookmarked successfully.',

    // Alarm
    'ALARM_CREATED' => 'Alarm has been created successfully',
    'ALARM_UPDATED' => 'Alarm has been updated successfully',
    'ALARM_DELETED' => 'Alarm has been deleted successfully',
    'ALARM_STATUS_CHANGE' => ' Alarm Status has been change successfully',
    'ALARM_STATE_CHANGE' => ' Alarm state has been change successfully',
    
    // Language
    "LANGUAGE_CREATED"=>'Language has been created successfully',
    "LANGUAGE_UPDATED"=>'Language has been updated successfully',
    "LANGUAGE_STATUS_CHANGE"=>'Language status has been change successfully',
    "LANGUAGE_DELETED"=>'Language has been deleted successfully',
    //
    "TWEAK_CREATED"=>'Tweak has been created successfully',
    "TWEAK_UPDATED"=>'Tweak has been updated successfully',
    "TWEAK_DELETED"=>'Tweak has been deleted successfully',
    // Add Product
    "PRODUCT_CREATE_SUCCESS"=>'Product has been created successfully',
    //Edit product
    "PRODUCT_LIST_SUCCESS"=>'Product has been listed successfully',
    "PRODUCT_UPDATE_SUCCESS"=>'Product has been updated successfully',
    "PRODUCT_MEDIA_UPLOAD"=>'Product media has been uploaded successfully',
    "PRODUCT_APPROVE_STATUS"=>'Product has been approved successfully',
    "PRODUCT_REJECT_STATUS"=>'Product has been rejected successfully',
    "PRODUCT_PUBLISH_STATUS"=>'Product has been published successfully',
    "PRODUCT_STATUS_CHANGE"=>'Product status has been changed to :status successfully',
    "PRODUCT_STATE_CHANGE"=>'Product state has been changed to :status successfully',
    //Delete product
    "PRODUCT_DELETE_SUCCESS"=>'Product has been deleted successfully',
    "PRODUCT_CANT_DELETE"=>"You can't delete this product. It's not in pending state",
    // Points Share
    'POINTS_SHARED' => 'Points has been shared successfully',
    
    //Refer friend
    'INVITE_CREATED'=>'Invitation has been sent successfully.',
    'RECORD_NOT_FOUND'=>'Record not found',
    //Follow Unfollow
    'USER_FOLLOW'=>'Following successfully.',
    'USER_UNFOLLOW'=>'Unfollowing successfully.',
    //Support UnSupport
    'USER_SUPPORT'=>'Supporting successfully.',
    'USER_UNSUPPORT'=>'Unsupporting successfully.',
    //Bookmark
    'BOOKMARK_ADDED'=>'Added to bookmark successfully.',
    'BOOKMARK_REMOVED'=>'Removed from bookmark successfully.',
    
    //POST
    'POST_UNLIKE'=>'Post is set as unlike',
    'POST_LIKE'=>'Post is set as like',
    'COMMENT_CREATED'=> 'Comment done successfully',
    'COMMENT_DELETED'=> 'Comment deleted successfully',
    'POST_IS_NOT_EXCLUSIVE' => 'Post is set as not exclusive',
    'POST_IS_EXCLUSIVE' => 'Post is set as exclusive',
    'POST_PIN_UPDATE_SUCCESS' => 'Post pin has been update successfully',
    'SETTINGS_UPDATED' => 'User Setting Updated',

    'ALARM_MEDIA_DELETED'=>'Alarm media has been deleted succesfully',
    'DOWNLOAD_PACK_SAVED' => 'Download pack has been saved successfully',
    'ALARM_SCHEDULE_CREATED'=> 'Alarm schedule has been created successfully',
    'ALARM_SCHEDULE_UPDATED'=> 'Alarm schedule has been updated successfully',
    'ALARM_SCHEDULE_DELETED'=> 'Alarm schedule has been deleted successfully',
    

    //Wallet
    'WALLET_LOCKED'=>'Wallet locked successfully.',
    'WALLET_UNLOCKED'=>'Wallet unlocked successfully.',
    'WALLET_PIN_CHANGED'=>'Wallet pin changed successfully.',
    'WALLET_QUESTION_SET'=>'Wallet security question set successfully.',
    'WALLET_PIN_SET'=>'Wallet pin set successfully.',

    'POST_CREATED_DRAFTMODE' => 'Post Created under draft mode',
    'POST_MEDIA_CREATED' => 'Post Media Created',
    'POST_CREATED' => 'Post Successfully Created with media',
    'POST_DELETED' => 'Post Deleted Successfully',
    'POST_UPDATED_DRAFTMODE' => 'Post Updated Successfully',
    'POST_MEDIA_UPDATED' => 'Post Media Updated Successfully',
    'POST_MEDIA_DELETED' => 'Post Media Deleted Successfully',

    'POST_MEDIA_UNLIKE'=>'Post media is set as unlike',
    'POST_MEDIA_LIKE'=>'Post media is set as like',
    'MEDIA_COMMENT_CREATED' => 'Media comment done successfully',
    'MEDIA_COMMENT_DELETED' => 'Media comment deleted successfully',
    

    //Brand Offer
    "BRAND_OFFER_CREATED"=>'Brand Offer has been created successfully',
    "BRAND_OFFER_UPDATED"=>'Brand Offer has been updated successfully',
    "BRAND_OFFER_STATUS_CHANGE"=>'Brand offer status has been change successfully',
    "BRAND_OFFER_STATE_CHANGE"=>'Brand offer state has been change successfully',
    "BRAND_OFFER_DELETED"=>'Brand Offer has been deleted successfully',
    
    "STATUS_CHANGED_SUCCESSFULLY" => 'User Status Changes SuccessFully',
    
    //Document delete
    'DOCUMENT_DELETE' => 'Document has been deleted successfully',
    //Csr Certificates
    'CSR_CREATED' => 'Csr certificate has been created successfully',
    'CSR_UPDATED' => 'Csr certificate has been updated successfully',
    'CSR_STATUS_CHANGE'=>'Csr certificate status has been change successfully',
    'CSR_DELETED'=>'Csr certificate has been deleted successfully',

	//Ads Messages
    'ADS_CREATE_SUCCESS' => 'Ads has been created successfully',
    'ADS_LIST_SUCCESS' => 'Ads has been listed successfully',
    'ADS_UPDATE_SUCCESS' => 'Ads has been updated successfully',
    'ADS_DOCUMENT_DELETE' => 'Ads document has been deleted successfully',
    'ADS_DELETE' => 'Ads has been deleted successfully',
    'ADS_STOP' => 'Ads has been stopped successfully',
    'ADS_STATUS_CHANGE' => 'Ads status has been changed successfully',

    'TAGLINE_UPDATED_SUCCESSFULLY' => 'Tagline Updated Successfully',
    
    'RESEND_ACTIVATION_SUCCESSFULLY' => 'Activation link resent successfully',

    // product bookmark
    'PRODUCT_BOOKMARK_ADDED'=>'Product has been bookmarked successfully.',
    'PRODUCT_BOOKMARK_REMOVED'=>'Product has been removed from bookmarked successfully.',

    //Add to cart
    'PRODUCT_ADDED_TO_CART' => 'Product has been added to cart successfully',
    'PRODUCT_ALREADY_ADDED_TO_CART' => 'Product has already been added to cart',
    'CART_PRODUCT_REMOVED' => 'Product has removed from cart',
    'CART_PRODUCT_LIST_SUCCESS' => 'Cart products has been listed successfully',
    'ORDER_PLACE_SUCCESS' => 'Your order has been placed successfully',
    'PROFILE_UPDATED' => 'User Profile updated successfully',
    'MY_BAG_PRODUCT_LIST_SUCCESS' => 'My bag products have been listed successfully',
    'PRODUCT_CLAIM_DETAILS' => 'Product claim details have been listed successfully',
    'PRODUCT_CLAIM_SUCCESS' => 'Product has been claimed successfully',
    'PRODUCT_REJECT_SUCCESS' => 'Product has been rejected successfully',
    'GIFT_ACCEPT_SUCCESS' => 'Product has been accepted successfully',
    'ORDER_GIFTED_SUCCESS' => 'Product has been gifted successfully',
    
    //Manage Coupon
    'COUPON_CREATED' => 'Coupon has been created successfully',
    'COUPON_UPDATED' => 'Coupon has been updated successfully',
    'COUPON_STATUS_CHANGE' => 'Coupon status has been changed successfully',
    'COUPON_DELETED' => 'Coupon has been deleted successfully',

    //Manage Coupon
    'COUPON_CREATED' => 'Coupon has been created successfully',
    'COUPON_UPDATED' => 'Coupon has been updated successfully',
    'COUPON_STATUS_CHANGE' => 'Coupon status has been changed successfully',
    'COUPON_DELETED' => 'Coupon has been deleted successfully',
    
    'SETTINGS_DETAIL' => 'Setting Detail',
    'POST_LISTING' => 'Post Listed Successfully',
    
    //Rack rate
    'RACK_RATE_LIST_SUCCESS' => 'Rack rates have been listed successfully',
    'RACK_RATE_UPDATE_SUCCESS' => 'Rack rate has been updated successfully',
    
    //User bookmark
    'USER_BOOKMARK_SUCCESS' => 'User has been bookmarked successfully',
    'USER_UNBOOKMARK_SUCCESS' => 'User has been unbookmarked successfully',
    'BOOKMARK_USER_LIST' => 'Bookmark users have been listed successfully',
];