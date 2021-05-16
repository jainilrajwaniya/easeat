<?php

return [
    'credentials' => [
        'key' => env('AWS_S3_KEY'),
        'secret' => env('AWS_S3_SECRET'),
    ],
    'region' => env('AWS_S3_REGION'),
    'version' => env('AWS_S3_VERSION'),
    // You can override settings for specific services
    'Ses' => [
        'region' => env('AWS_S3_SES_REGION'),
    ],
    's3_bucket' => env('AWS_S3_BUCKET'),
    'aws_s3_url' => env('AWS_S3_URL'),
    'aws_s3_kitchen_images_bucket' => '/uploads/chef/kitchens/',
    'aws_s3_kitchen_items_images_bucket' => '/uploads/kitchen-menu/',
    'aws_s3_promocode_images_bucket' => '/uploads/promo-code/',
    'thumbnails_200x200' => '/thumbnails/200x200/',
];
