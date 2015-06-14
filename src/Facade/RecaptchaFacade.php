<?php

namespace RecaptchaLaravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Description of Recaptcha
 *
 * @author sly
 */
class RecaptchaFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'recaptcha';
    }
}
