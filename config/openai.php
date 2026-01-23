<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    |
    | For Azure, this should be your Azure API Key.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Type
    |--------------------------------------------------------------------------
    |
    | Here you may specify the API type to use. The default is 'openai', but
    | you can set it to 'azure' to use the Azure OpenAI API.
    */
    'api_type' => env('OPENAI_API_TYPE', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Version
    |--------------------------------------------------------------------------
    |
    | Here you may specify the API version to use. This is required for Azure.
    | e.g. '2023-05-15'
    */
    'api_version' => env('OPENAI_API_VERSION'),

    /*
    |--------------------------------------------------------------------------
    | Azure OpenAI Resource Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify the resource name for Azure OpenAI.
    | e.g. 'your-resource-name'
    */
    'azure_resource' => env('AZURE_OPENAI_RESOURCE'),


    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout may be used to specify the maximum number of seconds to wait
    | for a response. By default, the client will time out after 30 seconds.
    */

    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Models & Responses API
    |--------------------------------------------------------------------------
    |
    | Default GPT-5 family routing and Responses API configuration.
    | You can override via environment variables without code changes.
    */

    'models' => [
        'default' => env('OPENAI_MODEL_DEFAULT', 'gpt-5-mini'),
        'fast' => env('OPENAI_MODEL_FAST', 'gpt-5-nano'),
    ],

    'use_responses_api' => env('OPENAI_USE_RESPONSES', true),

    'responses' => [
        // low | medium | high — see GPT-5 prompting guide
        'reasoning_effort' => env('OPENAI_REASONING_EFFORT', 'medium'),
        'temperature' => env('OPENAI_TEMPERATURE', 0.5),
    ],
];
