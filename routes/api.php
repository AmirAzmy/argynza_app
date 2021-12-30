<?php
Route::group([
    'namespace' => 'User',
], function () {
    Route::group([
        'prefix' => 'admin',
    ], function () {
        Route::post('login', 'AdminAuthController@login');
        Route::put('edit', 'AdminAuthController@edit');
        Route::get('profile', 'AdminAuthController@profile');
        Route::post('send-notification', 'AdminNotificationController@sendNotification');
    });

    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
    });

    Route::group([
        'prefix' => 'password',
    ], function () {
        Route::post('forget', 'ForgetPasswordController@forgetPassword');
        Route::post('reset', 'ForgetPasswordController@resetPassword');
        Route::post('check', 'ForgetPasswordController@checkCode');
        Route::post('resend', 'ForgetPasswordController@resendCode');
    });

    Route::group([
        'prefix' => 'verification',
    ], function () {
        Route::post('confirm', 'VerificationController@confirm');
        Route::post('resend', 'VerificationController@resend');
        Route::post('check', 'VerificationController@checkCode');
    });
});

Route::group([
    'prefix' => 'project',
], function () {
    Route::post("/", "ProjectController@create");
    Route::put("/{id}", "ProjectController@update");
    Route::delete("/{id}", "ProjectController@delete");
    Route::get("/", "ProjectController@index");
    Route::get("/{id}", "ProjectController@get");
});
