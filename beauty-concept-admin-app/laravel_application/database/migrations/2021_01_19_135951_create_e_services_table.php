<?php
/*
 * File name: 2021_01_19_135951_create_e_services_table.php
 * Last modified: 2022.02.14 at 09:14:40
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEServicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_services', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('name')->nullable();
            $table->double('price', 10, 2)->default(0);
            $table->double('discount_price', 10, 2)->nullable()->default(0);
            $table->string('duration', 16)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('featured')->nullable()->default(0);
            $table->boolean('enable_booking')->nullable()->default(1);
            $table->boolean('available')->nullable()->default(1);
            $table->integer('salon_id')->unsigned();
            $table->timestamps();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_services');
    }
}
