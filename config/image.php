<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | Image Optimization Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the image optimization process
    |
    */

    'max_width' => 1920,
    'max_height' => 1080,
    'quality' => 80,

    /*
    |--------------------------------------------------------------------------
    | Supported Formats
    |--------------------------------------------------------------------------
    |
    | Supported image formats for optimization
    |
    */

    'supported_formats' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp'
    ],

    /*
    |--------------------------------------------------------------------------
    | Logo Settings
    |--------------------------------------------------------------------------
    |
    | Specific settings for partner logos
    |
    */
    'logos' => [
        'max_width' => 400,
        'max_height' => 400,
        'min_width' => 100,
        'min_height' => 100,
        'quality' => 90,
        'storage_path' => 'partenaires/logos',
        'allowed_formats' => ['jpg', 'jpeg', 'png', 'webp'],
        'max_size' => 2048 // 2MB
    ],

]; 