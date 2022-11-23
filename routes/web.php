<?php

use Datlechin\SanctumToken\Http\Controllers\SanctumTokenController;
use Illuminate\Support\Facades\Route;

Route::namespace('Datlechin\SanctumToken\Http\Controllers')->middleware(['web', 'core'])->group(function () {
    Route::prefix(BaseHelper::getAdminPrefix())->middleware('auth')->group(function () {
        Route::resource('sanctum-token', SanctumTokenController::class)
            ->except('edit', 'update', 'show');

        Route::delete('sanctum-token/items/destroy', [
            'as' => 'sanctum-token.deletes',
            'uses' => 'SanctumTokenController@deletes',
            'permission' => 'sanctum-token.destroy',
        ]);
    });
});
