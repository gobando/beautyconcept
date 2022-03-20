<?php
/*
 * File name: EServiceAPIControllerTest.php
 * Last modified: 2022.02.02 at 21:22:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace Tests\Http\Controllers\API;

use Illuminate\Http\Response;
use Tests\Helpers\TestHelper;
use Tests\TestCase;

class EServiceAPIControllerTest extends TestCase
{

    public function testShow()
    {

        $response = $this->json('get', 'api/e_services/17');
        $response->assertStatus(200);
    }

    public function testGetEServicesByCategory()
    {
        $queryParameters = [
            'with' => 'salon;salon.addresses;categories',
            'search' => 'categories.id:4',
            'searchFields' => 'categories.id:=',
        ];

        $response = $this->json('get', 'api/e_services', $queryParameters);
        $data = TestHelper::generateJsonArray(count($response->json('data')), [
            'available' => true,
            'salon' => [
                'accepted' => true,
            ]
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['data' => $data]);
    }

    public function testGetRecommendedEServices()
    {
        $queryParameters = [
            'only' => 'id;name;price;discount_price;price_unit;has_media;media;total_reviews;rate;available',
            'limit' => '6',
        ];

        $response = $this->json('get', 'api/e_services', $queryParameters);
        $data = TestHelper::generateJsonArray(count($response->json('data')), [
            'available' => true,
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['data' => $data]);
    }

    public function testGetFeaturedEServicesByCategory()
    {
        $queryParameters = [
            'with' => 'salon;salon.addresses;categories',
            'search' => 'categories.id:4;featured:1',
            'searchFields' => 'categories.id:=;featured:=',
            'searchJoin' => 'and',
        ];

        $response = $this->json('get', 'api/e_services', $queryParameters);
        $data = TestHelper::generateJsonArray(count($response->json('data')), [
            'available' => true,
            'featured' => true,
            'salon' => [
                'accepted' => true,
            ]
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['data' => $data]);
    }

    public function testGetAvailableEServicesByCategory()
    {
        $queryParameters = [
            'with' => 'salon;salon.addresses;categories',
            'search' => 'categories.id:3',
            'searchFields' => 'categories.id:=',
            'available_salon' => 'true'
        ];

        $response = $this->json('get', 'api/e_services', $queryParameters);
        $response->dump();
        $data = TestHelper::generateJsonArray(count($response->json('data')), [
            'available' => true,
            'salon' => [
                'available' => true,
                'accepted' => true,
            ]
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['data' => $data]);
    }

    public function testDestroy()
    {

    }
}
