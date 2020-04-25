<?php

namespace App\Service\Validator\Helper;

class ValidationHelper
{
    public function isEmpty($var) {
        $isEmpty = false;
        if(filter_var($var, FILTER_VALIDATE_INT) !== 0 && empty($var)) {
            $isEmpty = true;
        }
        return $isEmpty;
    }
}