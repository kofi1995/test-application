<?php


namespace App\Service\Validator;


class RequiredWithoutAllRule extends RequiredRule
{
    private array $keys;

    public function __construct(array $keys)
    {
        parent::__construct();
        $this->keys = $keys;
    }

    protected function rule(): void
    {
       foreach($this->keys as $key) {
           if($this->request->has($key) && !$this->helper->isEmpty($this->request->get($key))) {
               $this->isValid = true;
               break;
           }
       }
       if(!$this->isValid) {
            parent::rule();
       }
        $this->message = "'$this->key' field is required because these fields do not exist in request: " . implode(',', $this->keys);
    }
}