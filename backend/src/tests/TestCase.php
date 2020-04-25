<?php

namespace Tests;

use App\Service\Container;
use App\Service\Helper;
use App\Service\Request;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit\Framework\MockObject\Stub;
use App\Service\CSVDatabase;


abstract class TestCase extends PHPUnitTestCase
{
    /** @var Helper|Stub */
    protected Stub $helper;
    /** @var Request|Stub */
    protected Request $request;
    protected Container $container;

    protected function setUp(): void
    {
        $this->request = new Request;
        $this->helper = $this->createStub(Helper::class);
        // Configure the stub.
        $this->helper->method('jsonResponse')
            ->will($this->returnCallback([$this, 'getJsonResponse']));
        $this->helper  ->method('parseCSVFile')
            ->willReturn($this->CSVData());

    }

    protected function searchID(int $id) {
            return array_search(['id' => $id][key(['id' => $id])], array_column($this->CSVData(), key(['id' => $id])));
    }

    protected function CSVData() {
        return [
            [
                'id' => 1,
                'name' => 'John Brown',
                'state' => "GA",
                "zip" => "08234",
                'amount' => 23.54,
                "quantity" => 5,
                "item" => '123',
            ],
            [
                'id' => 2,
                'name' => 'James Gerson',
                'state' => "FL",
                "zip" => "21256",
                'amount' => 67.54,
                "quantity" => 9,
                "item" => '876',
            ],

        ];
    }

    public function getJsonResponse(array $message = null, int $code = 200) {
        // return the encoded json
        return json_encode([
            'status' => $code < 300,
            'data' => $message,
            'code' => $code,
        ]);
    }

    public function requestMock(array $requestArray, bool $has = true, bool $validate = true) {
        $request = new Request;
        foreach ($requestArray as $key => $val) {
            $request->set($key, $val);
        }
        return $request;
    }

    public function editDBMock(int $id, array $requestArray) {
        $indexKey = $this->searchID($id);
        $csv = $this->CSVData();
        foreach ($requestArray as $key => $val) {
            $csv[$indexKey][$key] = $val;
        }
        $db = $this->createStub(CSVDatabase::class);
        // Configure the stub.
        $db->method('getAll')
            ->willReturn($csv);
        $db->method('initialize');
        return $db;
    }

    public function validateMethod() {
        return true;
    }
}
