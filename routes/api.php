<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeatController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UploaderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//user
Route::get('userMusic/{id}', [UserController::class, 'show'])->where(['id' => '[0-9]+']);
Route::get('/musics', [UserController::class, 'index']);
Route::get('/cats', [UserController::class, 'cats']);
Route::get('/feats', [UserController::class, 'feats']);
Route::get('/artists', [UserController::class, 'artist']);
Route::get('/tops', [UserController::class, 'topMusic']);
Route::get('/titles', [UserController::class, 'titleMusic']);
Route::post('/like', [UserController::class, 'like']);
Route::post('/disLike', [UserController::class, 'unLike']);
Route::post('musicByFilter', [UserController::class, 'musicByfilter']);

//Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('refreshToken', [AuthController::class, 'refreshToken']);

//Admin
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('user', [AuthController::class, 'user']);
    //artist
    Route::resource('artist', ArtistController::class)->where(['artist' => '[0-9]+']);
    //feat
    Route::resource('feat', FeatController::class)->where(['feat' => '[0-9]+']);
    //category
    Route::resource('category', CategoryController::class)->where(['category' => '[0-9]+']);
    //music
    Route::resource('music', MusicController::class)->where(['music' => '[0-9]+']);
   // upload
    Route::post('upload', [UploaderController::class, 'store']);
    Route::patch('upload/{id}', [UploaderController::class, 'update']);
});