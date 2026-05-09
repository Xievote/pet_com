<?php

use think\Route;

// 主页路由
Route::get('/', 'index/Pet/index');
Route::get('/index', 'index/Pet/index');

// 登录路由
Route::get('login', 'index/Login/login');
Route::post('login', 'index/Login/doLogin');
Route::get('register', 'index/Login/register');
Route::post('register', 'index/Login/doRegister');
Route::get('logout', 'index/Login/logout');

// 个人信息路由
Route::get('profile', 'index/UserController/profile');
Route::post('profile', 'index/UserController/updateProfile');

// 宠物记录路由
Route::post('pet/save', 'index/Pet/save');
Route::delete('pet/log/:id', 'index/Pet/deleteLog');

// 帖子路由
Route::get('post/detail/:id', 'index/PostController/detail');
Route::get('post/edit/:id', 'index/PostController/edit');
Route::post('post/update/:id', 'index/PostController/update');
Route::get('post/delete/:id', 'index/PostController/delete');
Route::get('post/create', 'index/PostController/create');
Route::post('post/save', 'index/PostController/save');
Route::get('post', 'index/PostController/index');

// 评论路由
Route::post('comment/save', 'index/Comment/save');
Route::get('comment/delete/:id', 'index/Comment/delete');