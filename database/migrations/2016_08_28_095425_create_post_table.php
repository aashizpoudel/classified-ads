<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('slug');
            $table->longText('post_content');
            $table->string('feature_image');
            $table->enum('type', ['post', 'page'])->default(null);
            $table->enum('status', [0,1,2])->default(0);
            $table->tinyInteger('show_in_header_menu');
            $table->tinyInteger('show_in_footer_menu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
