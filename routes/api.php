<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FBStuff;
use App\Http\Controllers\LoveSOS;
use App\Http\Controllers\Home;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group( function()
{    
    Route::post('checkIn', [FBStuff::class, 'checkIn']);
} );

Route::middleware(['auth:sanctum'])->prefix('v1')->group( function()
{
    Route::post('letMeIn', [FBStuff::class, 'letMeIn']);
    Route::post('updateProfile', [FBStuff::class, 'updateProfile']);
    Route::post('updatePhoto', [FBStuff::class, 'updatePhoto']);
    Route::post('setTheme', [FBStuff::class, 'setTheme']);
    Route::post('createUser', [FBStuff::class, 'createNewUser']);
    Route::post('newMessage', [FBStuff::class, 'newMessage']);
    Route::post('sendFCMToken', [FBStuff::class, 'sendFCMToken']);
    Route::post('requestHelp', [LoveSOS::class, 'requestHelp']);

    // get help request from uuid
    Route::get('getHelpRequest/{uuid}', [LoveSOS::class, 'getHelpRequest']);

    // volunteer
    Route::get('volunteer/{uuid}', [LoveSOS::class, 'volunteer']);

});