<?php


namespace App\Service\Validator;


class ArrayRule extends BaseRule
{
    public function __construct(int $size)
    {
        parent::__construct();
    }

    protected function rule(): void
    {
        $this->message = "'$this->key' field must be an array";
        $this->isValid = $this->helper->isEmpty($this->value) || is_array($this->value);
    }
}