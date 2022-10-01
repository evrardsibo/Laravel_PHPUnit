<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */    
    /**
     * test_an_order_can_be_starage_in_database
     * sotre in database
     * @return void
     */
    public function test_an_order_can_be_starage_in_database(): void
    {
        $response = $this->post('orders', [
            'label' => 'laravel',
            'sends_at' => Carbon::tomorrow()
        ]);

        //$response->assertOk();
        $response->assertRedirect(route('welcome'));
        $this->assertCount(1, Order::all());
    }
    
    /**
     * test_on_order_can_not_be_null
     * the fields not be empty
     * @return void
     */
    public function test_on_order_can_not_be_null()
    {
        //$this->withoutExceptionHandling();

        $response = $this->post('orders', [
            'label' => '',
            'sends_at' => ''
        ]);

        $response->assertSessionHasErrors(['label', 'sends_at']);
    }

    public function test_an_order_can_be_update(): void
    {
        $this->withoutExceptionHandling();
        
        $this->post('orders', [
            'label' => 'laravel',
            'sends_at' => Carbon::tomorrow()
        ]);

        $order= Order::first();

        $response = $this->put('orders/' . $order->id, [
            'label' => 'laravel1',
            'sends_at' => Carbon::now()->addDays(2)
        ]);

        $this->assertEquals('laravel1', Order::first()->label);
        $this->assertEquals(Carbon::now()->addDays(2), Order::first()->sends_at);

        $response->assertRedirect(route('orders.show', $order));

    }

    public function test_on_order_can_be_delete()
    {
        //$this->withoutExceptionHandling();

        $this->post('orders', [
            'label' => 'laravel',
            'sends_at' => Carbon::tomorrow()
        ]);

        $order = Order::first();

        $this->delete('orders/' . $order->id);

        $this->assertCount(0, Order::all());
    }
}
