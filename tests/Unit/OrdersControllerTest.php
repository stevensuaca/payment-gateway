<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase; 
use App\Order;

class OrdersControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /*
    public function testExample()
    {
        $this->assertTrue(true);
    }
    */

    /**
     * @test
     */
    public function create_new_order(){
        
        $response = $this->post('orders', [
            'customer_name' => 'Steven',
            'customer_email' => 'steven@mail.com',
            'customer_mobile' => '3207116'
        ]);

        $order = Order::first();

        $this->assertEquals($order->customer_name,'Steven');
        $this->assertEquals($order->customer_email,'steven@mail.com');
        $this->assertEquals($order->customer_mobile,'3207116');
    }


    /**
     * @test
     */
    public function redirect_to_orders(){
        $response = $this->get('/')->assertRedirect('/orders');
    }

    
}
