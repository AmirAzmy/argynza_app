<?php
Route::group([
    'namespace' => 'User',
], function () {
    Route::group([
        'prefix' => 'admin',
    ], function () {
        Route::post('login', 'AdminAuthController@login');
        Route::put('edit-admin', 'AdminAuthController@edit');
        Route::get('profile', 'AdminAuthController@profile');
        Route::post('send-notification', 'AdminNotificationController@sendNotification');
    });

    Route::group([
        'prefix' => 'users',
    ], function () {
        Route::get('/', 'UserController@index');
        Route::get('profile', 'UserController@profile');
        Route::post('change-password', 'UserController@changePassword');
        Route::get('/logged', 'UserController@loggedUser');
        Route::post('/', 'UserController@create');
        Route::put('{id}', 'UserController@update');
        Route::put('{id}/user-activation', 'UserController@userActivation');
        Route::delete('/{id}', 'UserController@delete');
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

    Route::group([
        'prefix' => 'notifications',
    ], function () {
        Route::get('/', 'NotificationController@index');
        Route::post('/', 'NotificationController@markAllAsRead');
        Route::post('/{id}', 'NotificationController@markAsRead');
        Route::get('/total-unread-notifications', 'NotificationController@totalUnreadNotifications');
    });

    Route::group([
        'prefix' => 'attendance',
    ], function () {
        Route::post("/checkin-and-out", "AttendanceController@checkInAndOut");
        Route::post("/check-location", "AttendanceController@checkLocation");
        Route::post("/export", "AttendanceController@exportUserAttendances");
        Route::post("/export-site-attendances", "AttendanceController@exportSiteAttendance");
        Route::get("/site-attendances", "AttendanceController@siteAttendances");
        Route::get("/user-attendances", "AttendanceController@userAttendances");
    });

});

Route::group([
    "prefix" => "home",
], function () {
    Route::get("dashboard", "HomeController@dashboard");
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

Route::group([
    'prefix' => 'sites',
], function () {
    Route::post("/", "SiteController@create");
    Route::put("/{id}", "SiteController@update");
    Route::delete("/{id}", "SiteController@delete");
    Route::get("/", "SiteController@index");
    Route::get("/{id}", "SiteController@get");
});


Route::group([
    'prefix' => 'request',
], function () {
    Route::post("/", "RequestController@create");
    Route::put("/{id}", "RequestController@changeStatus");
    Route::get("/", "RequestController@index");
    Route::get("/{id}", "RequestController@get");
});
