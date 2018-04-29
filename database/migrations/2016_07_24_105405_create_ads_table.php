<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('description');

            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->integer('brand_id');
            $table->enum('type', ['personal', 'business']);
            $table->enum('ad_condition', ['new', 'used']);
            $table->string('model');
            $table->decimal('price', 12,2);
            $table->enum('is_negotiable', [0,1]);

            $table->string('seller_name');
            $table->string('seller_email');
            $table->string('seller_phone');

            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->string('address');
            $table->string('video_url');

            //0 =pending for review, 1= published, 2=blocked, 3=archived
            $table->enum('status', [0,1,2,3]);
            $table->enum('price_plan', ['regular','premium']);
            $table->enum('mark_ad_urgent', ['0','1']);

            $table->integer('view');
            $table->integer('max_impression');
            $table->integer('user_id');
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
        Schema::drop('ads');
    }
}
