<?php

use core\Route;

Route::get('/','Home\Index','index');
Route::get('/gege','Home\Index','index');
Route::get('/back','Home\Index','back');
Route::get('/admin/index','admin\Index','index');
Route::get('/admin/detail','admin\Index','detail');

//Route::get('/{id}', 'Home\Index','indexcat');
Route::get('/blog/detail/{id}/{type}/{sex}','Home\Blog','detail');
Route::get('/user/login','Home\User','login');
Route::get('/user/registe','Home\Userr','registe');
Route::get('/user/back_url','Home\User','back_url');
