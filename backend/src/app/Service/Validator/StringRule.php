<?php


namespace App\Service\Validator;


class StringRule extends BaseRule
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function rule() : void
    {
        $this->isValid = $this->helper->isEmpty($this->value) || is_string($this->value);
        $this->message = "'$this->key' field must be a string";
    }
}