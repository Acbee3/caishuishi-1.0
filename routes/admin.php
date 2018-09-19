<?php
//系统总后台的功能路由写在此文件中

Route::get('login/login', 'LoginController@loginForm');
Route::post('login/login', 'LoginController@login');


Route::group(['middleware' => ['admin']], function () {
    Route::get("login/logout", "LoginController@logout");
    Route::get("index/index", "IndexController@index");

    //代账公司管理
    Route::get("agent/index", "AgentController@index");
    Route::any("agent/create", "AgentController@create");
    Route::any("agent/edit", "AgentController@edit");

    //用户管理
    Route::get("user/index", "UserController@index");

    Route::any("user/create", "UserController@create");
    Route::any("user/edit", "UserController@edit");
    Route::any("user/freeze", "UserController@freeze");

    //图片上传
    Route::any("upload/img", "UploadController@img");

    //文件上传
    Route::get("upload/file", "UploadController@file");
    Route::post("upload/file", "UploadController@file");

    //Route::resource("user","UserController");
});