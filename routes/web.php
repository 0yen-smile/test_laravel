<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController; 

//TOPページの表示
Route::get('/items', [ItemController::class, 'index'])->name('items.index');

//商品一覧の表示
Route::post('/items', [ItemController::class, 'store'])->name('items.store');

//削除（論理削除）処理
Route::delete('/items', [ItemController::class, 'delete'])->name('items.delete');

//復元処理
Route::post('/items/restore', [ItemController::class, 'restore'])->name('items.restore');

//完全削除ページの表示
Route::get('/items/force_delete', [ItemController::class, 'showForceDeletePage'])->name('items.forceDeletePage');

//完全削除処理
Route::delete('/items/force_delete', [ItemController::class, 'forceDelete'])->name('items.forceDelete');

//編集ページの表示
Route::get('/items/update', [ItemController::class, 'showUpdatePage'])->name('items.updatePage');

//編集処理
Route::put('/items/update', [ItemController::class, 'update'])->name('items.update');
