<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::middleware(['auth:api'])->group(function () {

    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $output = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'udpated_at' => $user->updated_at,
            'avatar_url' => $user->profile->avatar_url,
            'location' => $user->profile->location,
            'website' => $user->profile->website,
            'bio' => $user->profile->bio,
        ];
        return response()->json($output);
    })->middleware('scopes:user_info');


});