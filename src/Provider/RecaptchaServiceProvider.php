<?php

namespace RecaptchaLaravel\Provider;

use App\Recaptcha\Core\RecaptchaClient;
use App\Recaptcha\Facade\RecaptchaFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Validator;

use App\Recaptcha\Validator\RecaptchaValidator;
use ReCaptcha\ReCaptcha;

class RecaptchaServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias('Recaptcha', '\Recaptcha\Facade\RecaptchaFacade');

        Validator::resolver(function ($translator, $data, $rules, $messages) {
            $recaptchaValidator = new RecaptchaValidator($translator, $data, $rules, $messages);

            $recaptchaValidator->setRecaptcha($this->app['recaptcha']);

            return $recaptchaValidator;
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('\ReCaptcha\ReCaptcha', function ($app) {
            $config = config('recaptcha');

            return new ReCaptcha(
                $config['secret']
            );
        });

        $this->app->bind('recaptcha', function ($app) {
            $config = config('recaptcha');

            return new RecaptchaClient(
                $config['site_key'],
                $app['\ReCaptcha\ReCaptcha'],
                $app['Illuminate\Http\Request'],
                $app['Illuminate\Contracts\Logging\Log']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('recaptcha');
    }

}