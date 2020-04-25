<?php


namespace App\Service\Validator;


class RequiredRule extends BaseRule
{

    public function __construct()
    {
        parent::__construct();
    }
    protected function rule(): void
    {
       if(!$this->helper->isEmpty($this->value)) {
           $this->isValid = true;
       }
       $this->message = "'$this->key' field is required";
    }
}