<?php /*
 * File name: FaqCategoryFactory.php
 * Last modified: 2022.02.02 at 19:13:53
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

/** @noinspection PhpUnusedLocalVariableInspection */

use App\Models\FaqCategory;
use Illuminate\Database\Eloquent\Factory;

global $i;
$i = 0;

/** @var Factory $factory */
$factory->define(FaqCategory::class, function () use ($i) {
    $names = ['Service', 'Payment', 'Support', 'Salons', 'Misc'];
    return [
        'name' => $names[$i++],
    ];
});
