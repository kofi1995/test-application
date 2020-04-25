<?php


namespace App\Controller;

use App\Exception\CSVDBInitializedFailException;
use App\Service\CSVDatabase;
use App\Service\Helper;
use App\Service\Request;

class BaseController
{
    protected Helper $helper;
    protected Request $request;
    protected CSVDatabase $db;

    public function __construct(Helper $helper, Request $request, CSVDatabase $db = null)
    {
        $this->helper = $helper;
        $this->request = $request;
        $this->db = !$db ? new CSVDatabase($this->helper) : $db;
        $this->db->setCSVPath('/data.csv');
        try{
            $this->db->initialize();
        } catch(CSVDBInitializedFailException $e) {
                return die($this->json([
                    "error" => $e->getMessage()
                ], 400));
        }

    }

    protected function json(array $message = null, int $code = 200) : string {
        return $this->helper->jsonResponse($message, $code);
    }

    protected function request(): Request {
        return $this->request;
    }
}