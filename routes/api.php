<?php

Route::post('auth/register', 'Auth\ApiController@register');
Route::post('auth/login', 'Auth\ApiController@login');
Route::post('auth/logout', 'Auth\ApiController@logout')->middleware('auth:airlock');
Route::post('auth/revoke/all/tokens', 'Auth\ApiController@revoke_all_tokens')->middleware('auth:airlock');

Route::group(['prefix'=>'pc'],function (){
//    接口：api/pc/pcType
    Route::post('pcType',[\App\Http\Controllers\Yhpc\YhpcController::class,'getPcType']);
//    上传文件
    Route::post('upload',[\App\Http\Controllers\Yhpc\YhpcController::class,'upload'])->middleware('auth:airlock');
//    删除图片
    Route::post('delImg',[\App\Http\Controllers\Yhpc\YhpcController::class,'delImg'])->middleware('auth:airlock');
//    添加配置
    Route::post('addpeizhi',[\App\Http\Controllers\Yhpc\YhpcController::class,'addPeiZhi'])->middleware('auth:airlock');
//    添加电脑硬件
    Route::post('addhard',[\App\Http\Controllers\Yhpc\YhpcController::class,'addHardWare'])->middleware('auth:airlock');
});
