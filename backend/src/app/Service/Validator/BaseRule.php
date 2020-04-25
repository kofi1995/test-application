<?php


namespace App\Service\Validator;


use App\Exception\ValidationException;
use App\Service\Request;
use App\Service\Validator\Helper\ValidationHelper;

abstract class BaseRule
{
    protected $value;
    protected string $key;
    protected Request $request;
    protected ValidationHelper $helper;
    protected bool $isValid = false;
    protected string $message = "Validation Failed";

    public function __construct()
    {
        $this->helper = new ValidationHelper;
    }

    abstract protected function rule() : void;

    public function validate(Request $request, $key) {
        $this->request = $request;
        $this->key = $key;
        $this->value = $request->get($key);
        $this->rule();
        if(!$this->isValid) {
            throw new ValidationException($this->message);
        }
        return true;
    }
}