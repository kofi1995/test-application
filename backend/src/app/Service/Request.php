<?php


namespace App\Service;

use App\Exception\ValidationException;
use App\Exception\RequestNotFoundException;
use App\Service\Validator\BaseRule;
use App\Service\Validator\Validator;

class Request
{
    private $request = null;

    private bool $isJson;

    public function __construct()
    {
        $this->request = json_decode(file_get_contents('php://input'), true);

        if ($this->request  === null) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->request = $_POST;
            } else {
                $this->request = $_GET;
            }
        }
        else {
            $this->isJson = true;
        }
    }

    public function set(string $key, $value): void {
        if(!is_array($this->request)) {
            $this->request = [];
        }
        $this->request[$key] = $value;
    }

    public function has(string $key): bool {
        return isset($this->request[$key]) ? true : false;
    }

    public function get(string $key, $default = null)
    {
        if(!isset($this->request[$key])) {
            if($default) {
                return $default;
            }
            return null;
        }
        return $this->request[$key];
    }

    public function getStrict(string $key)
    {
        if(!isset($this->request[$key])) {
            throw new RequestNotFoundException($key . " is not found in request.");
        }
        return $this->request[$key];
    }

    public function all(): ?array {
        if(!empty($this->request)) {
            return $this->request;
        }
        return null;
    }

    public function isJson(): bool {
        if(!$this->isJson) {
            $headers = getallheaders();
            if(isset($headers['Content-Type'])) {
                if(stripos($headers['Content-Type'], 'application/json')!== false){
                    $this->isJson = true;
                }
                elseif(stripos($headers['Accept'], 'application/json')!== false){
                    $this->isJson = true;
                }
            }
        }
        return $this->isJson;
    }

    public function validate(array $lists) {
        $errors = [];

        foreach($lists as $key => $value) {
            if(!is_array($value)) {
                throw new ValidationException("Request validation rules must be an array of validation rules");
            }
            foreach ($value as $rule) {
                if(!$rule instanceof BaseRule) {
                    throw new ValidationException(get_class($rule) . " is not a valid validation rule. Validation rules must be instance of type BaseRule.");
                }
                try {
                    Validator::make($key, $rule, $this);
                } catch(ValidationException $e) {
                    $errors[$key][] = $e->getMessage();
                }
            }
        }
        if(!empty($errors)) {
            throw new ValidationException("Request Validation failed", 0, null, $errors);
        }
        return true;
    }
}