<?php


namespace App\Service\Validator;


class IntegerRule extends BaseRule
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function rule(): void
    {
        $this->message = "'$this->key' field must be an integer";
        if ($this->helper->isEmpty($this->value) || filter_var($this->value, FILTER_VALIDATE_INT) !== 0 || filter_var($this->value, FILTER_VALIDATE_INT)) {
            $this->isValid = true;
        }
    }
}