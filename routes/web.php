<?php

use Illuminate\Support\Facades\Auth;



Route::get('/', 'main@authFunc');


Route::post('/follow_usr', 'followings@followUnfollow')->middleware('auth');

Route::get('/feed', 'feedController@showFeedPage')->middleware('auth');

Route::get('/popular/profiles', 'popularController@showProfiles');


Route::group(['prefix' => 'editor'], function () {
  Route::get('/inook', 'editor@New_inook')->middleware('auth');
  Route::get('/{writing_id}', 'editor@showEditor')->middleware('auth');
  Route::post('/{writing_id}/save', 'editor@saveChanges')->middleware('auth', 'EditorPermission');
  Route::post('/{writing_id}/uploadf', 'editor@saveUploads')->middleware('auth', 'EditorPermission');

  Route::post('/{writing_id}/info', 'editor@getInfo')->middleware('auth', 'EditorPermission');
});


Route::post('/tagInfo', 'algo\searchAlgo@tagsByText')->middleware('auth');

Route::post('/hm_scrollLoad', 'main@scrollDownload');



Route::get('storage/{folder}/{filename}', 'fileServe@serve');





Auth::routes();

Route::middleware(['usrindb'])->group(function () {
  Route::get('{str_id}/following', 'profileController@show_following');
  Route::get('{str_id}/auth_stories', 'profileController@show_auth_stories');
  Route::get('{str_id}/{_2_path?}', 'main@viewRedirector');

  Route::post('{str_id}/upload_avatar', 'profileController@edit')->middleware('profileAuth');

  Route::post('{str_id}/{_2_path}/progress', 'writings@inookProgress')->middleware('auth');
});

Route::post('{str_id}/{_2_path}/comment', 'writings@commCreate')->middleware('auth');
Route::post('{str_id}/{_2_path}/react', 'reactController@saveLike')->middleware('auth');
Route::post('{str_id}/{_2_path}/getsec', 'writings@getWritingInfo')->middleware('auth');





// TODO: add new paths to Writing->updatedInfo->generateStrId->banned
