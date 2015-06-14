## Recaptcha for Laravel

This is a very simple library that makes integrating the latest recaptcha mechanism into Laravel.

### Installing

Simply use composer e.g. 

```
composer require peterfox/recaptcha-laravel
```

### Implementing (Laravel 5.0/5.1)

Firstly create a config in the config folder called "recaptcha.php" containing:

```php
<?php

return array(
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret' => env('RECAPTCHA_SECRET', ''),
);
```

Then just add the correct details to your .env file in the project:

```
RECAPTCHA_SITE_KEY=site key from recaptcha
RECAPTCHA_SECRET=your private secret from recaptcha
```

Now add the service provider to your app config in the config folder:

```php
'providers' => [
	...
	'RecaptchaLaravel\Provider\RecaptchaServiceProvider'
]
```

And then add the Facade to your app config as well:

```php
aliases' => [
	...
	'Recaptcha' => 'RecaptchaLaravel\Facade\RecaptchaFacade',
]
```

### Using the form field and validator

The basics of using is as follows, in your form (example uses Blade but should work with raw PHP) just simply use the Facade:

```blade
{!! Recaptcha::field() !!}
```

This will output the basic field.

Then in your form validator simply use the recaptcha validator. Below you can see an example of the validator being put to use in a Request object.

```php
class ExamleRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'content' => 'required',
		];

        if (!app()->environment('test')) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return $rules;
	}

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Please tick the check box next to "I\'m not a robot" to validate you\'re human',
            'g-recaptcha-response.recaptcha' => 'Robot detected',
        ];
    }

}
```

In the example you'll see I've made it that the g-recaptcha-response field is only provided when I'm not using the 'test' environment, this is because if you're doing integration tests with something like Behat they'll always fail if recaptcha validation is enabled and at the same time Recaptcha checks for bots so using this mechanism in a test environment would be problematic at best.

### Tests

You can quickly run the PHPSpec tests with the following command after installing from composer

```
vendor/phpspec/phpspec/bin/phpspec run
```

### License

This was created by Peter Fox and is provided under the MIT open source license



