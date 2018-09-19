<?php

Route::get('/', "Agent\LoginController@LoginForm");

Route::group(['prefix'=>'errors'],function (){
    Route::get('/404',function (){
        return view('errors.404');
    });
});

Route::get("/hello",function (){
   return view("hello");
});