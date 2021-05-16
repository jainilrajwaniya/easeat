<?php

return [
    /*
     * Upload directory. Make sure this is can be accessed by public and writable.
     *
     * Default: public/uploads/images
     */
    'default_path' => public_path('uploads'),
    /*
     * Upload directory. Make sure this name is same as ur default_path.
     *
     * Default: public/uploads/images
     */
    'image_upload_path' => 'uploads',
    /*
     * add watermark image path to create watermark image
     *
     */
    'watermark_image' => public_path('watermark_logo.png'),
    /*
     * SET true to create resize images otherwise SET false
     *
     */
    'is_resize' => true,
    /*
     * SET true to create watermark image of original uploaded image otherwise SET false
     *
     */
    'is_watermark' => false,
    /*
     * SET true to create watermark image of resize uploaded image otherwise SET false
     *
     */
    'is_watermark_resize' => false,
    /*
     * Sizes, used to crop and create multiple size.
     *
     * array(width, height)
     */
    'dimensions' => [
        'size50' => [50, 50],
        'size200' => [200, 200],
        // 'size450' => [450, 450],
        // 'size400' => [400, 400]
    ]
];
