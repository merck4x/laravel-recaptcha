<?php

namespace RecaptchaLaravel\Validator;

use Recaptcha\Core\RecaptchaClient;
use Illuminate\Validation\Validator;

class RecaptchaValidator extends Validator
{

    /**
     * @var RecaptchaClient
     */
    private $recaptcha;

    public function validateRecaptcha($attribute, $value, $parameters)
    {
        return $this->recaptcha->validate($value);
    }
    /**
     * @param RecaptchaClient $recaptcha
     */
    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

}
