<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class ApiTest extends TestCase {
    public function testServerRunning() {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testAddNewReadingMissingParams() {
        $payload = [
            'farmId' => 1,
            'readingTypeId' => 1,
            'readingValue' => 12.43,
            'readingTime' => "",
            'readingType' => ""
        ];

        $response = $this->json('post','/api/reading/addnew', $payload);
        $response->assertExactJson(
            [
                "success" => false,
                "error_info" => "missing_parameters"
            ]
        );
    }

    public function testAddNewReadingInvalidValue() {
        $payload = [
            'farmId' => 1,
            'readingTypeId' => 1,
            'readingValue' => 123456.456,
            'readingTime' => "2022-01-01 12:34:00",
            'readingType' => "temperature"
        ];

        $response = $this->json('post','/api/reading/addnew', $payload);
        $response->assertExactJson(
            [
                "success" => false,
                "error_info" => "invalid_value"
            ]
        );
    }

    public function testAddNewFarmMissingParameters() {
        $payload = [
            'name' => "",
            'address' => "",
            'lat' => 60.333333,
            'long' => 23.453533,
        ];

        $response = $this->json('post','/api/farm/addnew', $payload);
        $response->assertExactJson(
            [
                "success" => false,
                "error_info" => "missing_parameters"
            ]
        );
    }

    public function testAddNewFarmNameExists() {
        $payload = [
            'name' => "Noora's farm",
            'address' => "Ajokatu 53, 15500 Lahti",
            'lat' => 60.97700800,
            'long' => 25.66947400,
        ];

        $response = $this->json('post','/api/farm/addnew', $payload);
        $response->assertExactJson(
            [
                "success" => false,
                "error_info" => "farm_name_exists"
            ]
        );
    }

    public function testGetFarmsFormat() {
        $response =   $this->json('get','/api/farms/all')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'farm_name',
                        'address',
                        'latitude',
                        'longitude',
                    ]
                ]
            );
    }

    public function testGetReadingsFormat() {
        $response =   $this->json('get','/api/readings/all')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'farm_id',
                        'date_time',
                        'reading_type_id',
                        'reading_value',
                        'reading_type',
                        'farm_name'
                    ]
                ]
            );
    }

    public function testGetReadingsByFarmIdFormat() {
        $response =   $this->json('get','/api/readings/farm/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'farm_id',
                        'date_time',
                        'reading_type_id',
                        'reading_value',
                        'reading_type',
                        'farm_name'
                    ]
                ]
            );
    }

    public function testGetReadingsByFarmIdNoFarm() {
        $response =   $this->json('get','/api/readings/farm/999999')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                ]
            );
    }

    public function testAddNewFarmNameSuccess() {
        $payload = [
            'name' => "Unique new farm 64",
            'address' => "Kulmakatu 3, 15140 Lahti",
            'lat' => 60.97700800,
            'long' => 22.66947400,
        ];

        $response = $this->json('post','/api/farm/addnew', $payload);
        $response->assertExactJson(
            [
                "success" => true,
                "error_info" => ""
            ]
        );
    }

    public function testAddNewReadingSuccess() {
        $payload = [
            'farmId' => 1,
            'readingTypeId' => 1,
            'readingValue' => 1.5,
            'readingTime' => "2022-01-14 13:34:00",
            'readingType' => "temperature"
        ];

        $response = $this->json('post','/api/reading/addnew', $payload);
        $response->assertExactJson(
            [
                "success" => true,
                "error_info" => ""
            ]
        );
    }

    public function testResetDatabaseToDefault() {
        $this->artisan('migrate:fresh --seed')->assertSuccessful();
    }
    
}
