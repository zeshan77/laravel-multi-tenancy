<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomerTest extends TestCase
{

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * test_it_creates_customer
     *
     * @return void
     */
    function test_it_creates_customer()
    {
        $data = [
            'name' => 'Zeshan Khattak',
            'address' => 'Hamburg, Germany',
            'email' => 'customer@email.com',
            'phone_number' => '+4911111111111',
        ];
        $this->post('/settings/customers/', $data);

        $this->assertDatabaseHas('customers', $data);

        $this->get('/customers/')->assertSee($data['name']);
    }

}
