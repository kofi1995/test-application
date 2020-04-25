<?php


namespace App\Service\Validator;


class SizeRule extends BaseRule
{
    private int $size;
    public function __construct(int $size)
    {
        parent::__construct();
        $this->size = $size;
    }

    protected function rule(): void
    {
        $this->message = "'$this->key' field's size must be $this->size.";
        if(is_int($this->value)) {
            $this->isValid = $this->value == $this->size;
        }
        elseif(is_string($this->value)) {
            $this->isValid = strlen($this->value) === $this->size;
        }
        elseif(is_array($this->value)) {
            $this->isValid = count($this->value) === $this->size;
        }
        elseif($this->helper->isEmpty($this->value)) {
            $this->isValid = true;
        }
    }
}