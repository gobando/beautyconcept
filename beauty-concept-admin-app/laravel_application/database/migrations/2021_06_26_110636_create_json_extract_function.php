<?php
/*
 * File name: 2021_06_26_110636_create_json_extract_function.php
 * Last modified: 2022.02.02 at 12:29:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Migrations\Migration;

class CreateJsonExtractFunction extends Migration
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
        CREATE FUNCTION `json_extract`(
        details TEXT,
        required_field VARCHAR (255)
        ) RETURNS TEXT CHARSET latin1
        BEGIN
        SET details = SUBSTRING_INDEX(details, "{", -1);
        SET details = SUBSTRING_INDEX(details, "}", 1);
        RETURN TRIM(
            BOTH \'"\' FROM SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(
                        details,
                        CONCAT(
                            \'"\',
                            SUBSTRING_INDEX(required_field,\'$.\', -1),
                            \'":\'
                        ),
                        -1
                    ),
                    \',"\',
                    1
                ),
                \':\',
                -1
            )
        ) ;
        END
        ');
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
