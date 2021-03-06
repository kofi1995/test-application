<?php


namespace App\Exception;


use Throwable;

class ValidationException extends BaseException
{
    private ?array $errors;

    public function __construct($message = "", $code = 0, Throwable $previous = null, ?array $errors = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): ?array {
        return $this->errors;
    }
}