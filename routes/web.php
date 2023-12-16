<?php

use Botble\Base\Facades\AdminHelper;
use FriendsOfBotble\SanctumToken\Http\Controllers\SanctumTokenController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::resource('sanctum-token', SanctumTokenController::class)
        ->except('edit', 'update', 'show');
}, ['web', 'core', 'auth']);
