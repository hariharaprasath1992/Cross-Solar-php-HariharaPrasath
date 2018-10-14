<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PanelTest extends TestCase
{

    use RefreshDatabase;

    // Success Scenarios
    /**
     * Testing Scenario : Valid Serial, longitude and Latitude
     *
     * @return void
     */
    public function testStoreSuccess()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0123456789',
            'longitude' => 0,
            'latitude'  => 0
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testing Scenario : Another Valid Serial (diiferent), longitude and Latitude
     *
     * @return void
     */
    public function testStoreSuccess_multipleSerial()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0011223344',
            'longitude' => 0,
            'latitude'  => 0
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testing Scenario : Valid Serial (diiferent), longitude (Lower Limit)  and Latitude
     *
     * @return void
     */
    public function testStoreFailure_checkLongitudeValue_LowerLimit()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0001112223',
          'longitude' => -180,
          'latitude'  => 12
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testing Scenario : Valid Serial (diiferent), longitude (Higher Limit)  and Latitude
     *
     * @return void
     */
    public function testStoreFailure_checkLongitudeValue_HigherLimit()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000111122',
          'longitude' => 180,
          'latitude'  => 12
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testing Scenario : Valid Serial (diiferent), longitude and Latitude (Lower Limit)
     *
     * @return void
     */
    public function testStoreFailure_checkLatitudeValue_LowerLimit()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000011111',
          'longitude' => 12,
          'latitude'  => -90
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testing Scenario : Valid Serial (diiferent), longitude and Latitude (Higher Limit)
     *
     * @return void
     */
    public function testStoreFailure_checkLatitudeValue_HigherLimit()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000001111',
          'longitude' => 12,
          'latitude'  => 90
        ]);

        $response->assertStatus(201);
    }

    // Failure Scenarios

    /**
     * Testing Scenario : Mandatory field check - Latitude
     *
     * @return void
     */
    public function testStoreFailure_withoutLatitude()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0000000001',
            'longitude' => 0
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Mandatory field check - Longitude
     *
     * @return void
     */
    public function testStoreFailure_withoutLongitude()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0000000002',
            'latitude'  => 0
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Mandatory field check - Serial
     *
     * @return void
     */
    public function testStoreFailure_withoutSerial()
    {
        $response = $this->json('POST', '/api/panels', [
            'longitude' => 12,
            'latitude'  => 0
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Mandatory fields check - Longitude & Latitude
     *
     * @return void
     */
    public function testStoreFailure_withoutLongitudeAndLatitude()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial' => 'abcdef0000000003'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Mandatory fields check - Serial, Longitude & Latitude
     *
     * @return void
     */
    public function testStoreFailure_withoutSerialAndLongitudeAndLatitude()
    {
        $response = $this->json('POST', '/api/panels', [

        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Serial Field length check - should not lower than 16 digits
     *
     * @return void
     */
    public function testStoreFailure_incorrectSerialLength_lower()
    {

        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef003',
            'longitude' => 12,
            'latitude'  => 0
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Serial Field length check - should not higher than 16 digits
     *
     * @return void
     */
    public function testStoreFailure_incorrectSerialLength_higher()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef000000000412345',
            'longitude' => 12,
            'latitude'  => 0
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Duplicate Serial validation
     *
     * @return void
     */
    public function testStoreFailure_duplicateSerial()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0123456789',
            'longitude' => 12,
            'latitude'  => 0
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Invalid Longitude Field type
     *
     * @return void
     */
    public function testStoreFailure_wrongLongitudeType()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000000007',
          'longitude' => 'Te',
          'latitude'  => 12
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Invalid Latitude Field type
     *
     * @return void
     */
    public function testStoreFailure_wrongLatitudeType()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000000006',
          'longitude' => 12,
          'latitude'  => 'Te'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Invalid Longitude value - higher than maximum range value (above 180)
     *
     * @return void
     */
    public function testStoreFailure_invalidLongitudeValue_postive()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000000008',
          'longitude' => 181,
          'latitude'  => 12
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Invalid Longitude value - lower than minimum range value (above -180)
     *
     * @return void
     */
    public function testStoreFailure_invalidLongitudeValue_negative()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000000009',
          'longitude' => -181,
          'latitude'  => 12
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Invalid Latitude value - higher than maximum range value (above 90)
     *
     * @return void
     */
    public function testStoreFailure_invalidLatitudeValue_postive()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000000008',
          'longitude' => 12,
          'latitude'  => 91
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testing Scenario : Invalid Latitude value - lower than minimum range value (above -90)
     *
     * @return void
     */
    public function testStoreFailure_invalidLatitudeValue_negative()
    {
        $response = $this->json('POST', '/api/panels', [
          'serial'    => 'abcdef0000000009',
          'longitude' => 12,
          'latitude'  => -91
        ]);

        $response->assertStatus(422);
    }
}
