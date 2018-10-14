<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test scenario: Valid Panel serial with Electricity
     *
     * @return void
     */
    public function testIndexForPanelWithElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(1, json_decode($response->getContent()));
    }

    /**
     * Test scenario: Valid Panel serial without Electricity
     *
     * @return void
     */
    public function testIndexForPanelWithoutElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(0, json_decode($response->getContent()));
    }

    /**
     * Test scenario: Invalid Panel serial
     *
     * @return void
     */
    public function testIndexWithoutExistingPanel()
    {
        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial=testserial');

        $response->assertStatus(404);
    }

    /**
     * Test scenario: Without passing the Panel serial
     *
     * @return void
     */
    public function testIndexWithoutPanelSerial()
    {
        $response = $this->json('GET', '/api/one_hour_electricities');

        $response->assertStatus(404);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSummaryForPanelWithElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();

        $response = $this->json('GET', '/api/one_day_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(1, json_decode($response->getContent()));
    }
}
