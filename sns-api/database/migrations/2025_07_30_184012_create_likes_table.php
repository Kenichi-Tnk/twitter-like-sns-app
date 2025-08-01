<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // Firebase UID
            $table->unsignedBigInteger('post_id'); // 投稿してID
            $table->timestamps();

            // 外部キー制約
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            // 同じユーザーが同じ投稿に複数回いいねできないように
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
