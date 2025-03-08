<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model {
    //登録可能カラムを指定
    protected $fillable = ['name', 'price'];

    use SoftDeletes;

    //削除日時を管理
    protected $dates = ['deleted_at'];

}