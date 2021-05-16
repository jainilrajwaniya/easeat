<?php

return [
    "ADD_SUBADMIN" => [ 
        'name' => 'required|min:4|max:25', 
        'email' => 'required|unique:admins|email', 
        // 'password' => 'required|min:8', 
        // 'confirm_password' => 'required|same:password', 
        'role' => 'required|in:SUPER_ADMIN,ADMIN' 
    ],
    "EDIT_SUBADMIN" => [ 
        'name' => 'required|min:4|max:25', 
        'email' => 'required|email',
        'role' => 'required|in:SUPER_ADMIN,ADMIN' 
    ],
    "ADD_CATEGORY" => [ 
        'category_name' => 'required|unique:categories'
    ],
    "EDIT_CATEGORY" => [ 
        'category_name' => 'required'
    ],
    "ADD_CUISINE" => [ 
        'cuisine_type_name' => 'required|unique:cuisine_types'
    ],
    "EDIT_CUISINE" => [ 
        'cuisine_type_name' => 'required'
    ],
    "EDIT_COUNTY" => [ 
        'name' => 'required',
        'country' => 'required'
    ],
    "EDIT_CITY" => [ 
        'name' => 'required',
        'state' => 'required'
    ],
    "EDIT_AREA" => [ 
        'name' => 'required',
        'city' => 'required'
    ],
];
