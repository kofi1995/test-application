<?php

namespace Tests\Feature;

use App\Controller\WelcomeController;
use App\Service\Container;
use App\Service\Helper;
use App\Service\Request;
use Tests\TestCase;


class OrdersTest extends TestCase
{


    protected WelcomeController $controller;
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new WelcomeController($this->helper, $this->request);
    }

    public function testGetOrders() {
        $this->assertEquals(
            $this->CSVData(),
            json_decode($this->controller->index(), true)['data']['data']
        );
    }
    public function testEditOrderValidationFailed() {
        $this->assertFalse(
            json_decode($this->controller->update(5), true)['status']
        );
        $this->assertEquals(
            422,
            json_decode($this->controller->update(1), true)['code']
        );
    }

    public function testEditOrderCannotFindID() {
        $request = $this->requestMock(['name' => "John Sanders"]);

        $controller = new WelcomeController($this->helper, $request);
        $this->assertFalse(
            json_decode($controller->update(5), true)['status']
        );
        $this->assertEquals(
            400,
            json_decode($controller->update(5), true)['code']
        );
    }

    public function testEditOrderIDFoundAndUpdateSuccess() {
        $requestArray = ['name' => "John Sanders"];
        $request = $this->requestMock($requestArray );

        $controller = new WelcomeController($this->helper, $request);
        $this->assertTrue(
            json_decode($controller->update(2), true)['status']
        );
        $this->assertEquals(
            200,
            json_decode($controller->update(2), true)['code']
        );
        $db = $this->editDBMock(2, $requestArray );
        $controller2 = new WelcomeController($this->helper, $request, $db);

        $this->assertTrue(
            in_array($requestArray, json_decode($controller2->index(), true))
        );
    }


}