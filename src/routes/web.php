<?php

// ROUTES BACKEND
Route::prefix('admin')->group(function () {

Route::post('loginAdmin', 'AdminController@loginAdmin')->name('loginAdmin');

Route::get('login', function () {
  return view('admigen::login');
})->name('admin.loginPage');


  Route::group(['middleware' => 'Admin'], function () {
    Route::get('/', function () {
      return redirect(route('admin.dashboard'));
    })->name('backend.home');


    Route::get('dashboard', 'AdminController@showDashboard')->name('admin.dashboard');

    // MODEL CLIENTS
    Route::get('{model}', 'AdminController@show')->name('admin.show');
    Route::get('{model}/create/', 'AdminController@new')->name('admin.add');
    Route::post('{model}/create', 'AdminController@store');
    Route::get('{model}/edit/{id}', 'AdminController@edit')->name('admin.edit');
    Route::post('{model}/edit/{id}', 'AdminController@update');
    Route::get('{model}/delete/{id}', 'AdminController@delete')->name('admin.delete');
    Route::post('{model}/export', 'AdminController@exportCSV')->name('admin.export');

    Route::get('delete-img/{model}/{field}/{id}/{img_id?}', 'AdminController@deleteImg')->name('deleteImg');
    Route::post('image/save', 'AdminController@saveImg');
    Route::post('file/save', 'AdminController@saveFile');
    Route::post('ordre/save', 'AdminController@reordonner')->name('admin.ordre');
    Route::post('image/utype', 'AdminController@updateImgType')->name('admin.updateImgType');
    Route::post('state/save', 'AdminController@changeState')->name('admin.state');


  });
});
