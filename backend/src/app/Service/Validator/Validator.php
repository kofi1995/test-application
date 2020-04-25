<?php


namespace App\Service\Validator;


use App\Service\Container;
use App\Service\Request;

class Validator
{
    protected Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function create(string $key, BaseRule $rule) {
        return $rule->validate($this->request, $key);
    }
    public static function make(string $key, BaseRule $rule, Request $request) {
        if(!$request) {
            $container = new Container;
            $validator = $container->get(Validator::class);
        }
        else {
            $validator = new Validator($request);
        }

        return $validator->create($key, $rule);
    }
}