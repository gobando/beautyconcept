<?php
/*
 * File name: 2021_06_26_110636_create_json_unquote_function.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Migrations\Migration;

class CreateJsonUnquoteFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::unprepared('
            CREATE FUNCTION `json_unquote`(`mdata` TEXT CHARSET utf8mb4) RETURNS text CHARSET utf8mb4
            BEGIN
            RETURN mdata;
            END');
        } catch (Exception $exception) {

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
