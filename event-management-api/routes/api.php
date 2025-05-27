<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'CORS is working!',
        'timestamp' => now(),
    ]);
});

Route::get('/cors-test', function () {
    return response()->json([
        'cors_headers' => [
            'Access-Control-Allow-Origin' => request()->header('Origin'),
            'Access-Control-Allow-Credentials' => 'true',
        ]
    ]);
});
