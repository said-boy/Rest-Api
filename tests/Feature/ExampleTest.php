<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_api_method_put_settings()
    {
        $response = $this->call('PUT', '/api/settings', [
            'overtime_method' => 2
        ]);
        $response->assertStatus(200);
    }

    public function test_api_method_post_create_employees()
    {
        $response = $this->call('POST', '/api/employees', [
            'name' => 'budi',
            'status_id' => 4,
            'salary' => '7000000'
        ]);
        $response->assertStatus(200);
    }

    public function test_api_method_get_employees()
    {
        $response = $this->call('GET', '/api/employees');
        $response->assertStatus(200);
    }

    public function test_api_method_post_create_overtimes()
    {
        $response = $this->call('POST', '/api/overtimes', [
            'employee_id' => 8,
            'date' => '2022-03-14',
            'time_started' => '06:00',
            'time_ended' => '09:00'
        ]);
        $response->assertStatus(200);
    }

    public function test_api_method_get_overtimes()
    {
        $response = $this->call('GET', '/api/overtimes');
        $response->assertStatus(200);
    }

    public function test_api_method_get_overtimes_pays_calculate()
    {
        $response = $this->call('GET', '/api/overtime-pays/calculate');
        $response->assertStatus(200);
    }
}
