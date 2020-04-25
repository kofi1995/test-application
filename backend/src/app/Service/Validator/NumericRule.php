<?php


namespace App\Service\Validator;


class NumericRule extends BaseRule
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function rule(): void
    {
        $this->message = "'$this->key' field must be numeric";
        if ($this->helper->isEmpty($this->value) || filter_var($this->value, FILTER_VALIDATE_INT) !== 0 || is_numeric($this->value)) {
            $this->isValid = true;
        }
    }
}