<?php
/*
 * File name: 2021_01_19_140427_create_categories_table.php
 * Last modified: 2022.02.14 at 09:14:40
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('name')->nullable();
            $table->string('color', 36);
            $table->longText('description')->nullable();
            $table->integer('order')->nullable()->default(0);
            $table->boolean('featured')->nullable()->default(0);
            $table->integer('parent_id')->nullable()->unsigned();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null')->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
