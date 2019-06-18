<?php

// tenant routes

// Declare base_domain in config/app.php that should read from .env
Route::group(['domain' => '{subdomain}.' . config('app.base_domain'), 'middleware' => ['tenant']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // to call this route form view:
    // {{ route('home', ['subdomain' => $subdomain]) }}
});

// non tenant routes
Route::get('/subscriptions', 'SubscriptionController@index')->name('subscriptions.index');
// to call this route form view:
// {{ route('subscriptions.index') }}