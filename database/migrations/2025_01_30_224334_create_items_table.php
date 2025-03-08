<?php

use Illuminate\Database\Migrations\Migration; //Migration：マイグレーションを扱うためのクラス
use Illuminate\Database\Schema\Blueprint; //Blueprint：テーブルのカラムを定義するためのクラス
use Illuminate\Support\Facades\Schema; //Schema：DBのスキーマを操作するためのファサード（シンプルな記述が出来るもの？）

return new class extends Migration
{
    /**
     * Run the migrations.
     * up()メソッドは、マイグレーションを適用する時に呼ばれる。
     * DBのテーブルを新規作成したり、カラムを追加したりする処理を記述する。
     */
    public function up(): void 
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); //id()とすることで、主キーカラムを追加する。BIGINT UNSIGNED（64ビットの符号なし整数） AUTO_INCREMENT PRIMARY KEYとなる。
            $table->string('name');
            $table->integer('price');
            $table->timestamps();
        });

        //Schema::create：新しいテーブルを作成するLaravelのメソッド
        //第１引数（'items'）は作成するテーブル名を指定
        //第２引数（function (Blueprint $table)）はテーブルのカラムを定義する無名関数。Blueprintクラスを通じてテーブルの設計を行う。

    }

    /**
     * Reverse the migrations.
     * down()メソッドはマイグレーションをロールバックするときに実行されるメソッド
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }

};
