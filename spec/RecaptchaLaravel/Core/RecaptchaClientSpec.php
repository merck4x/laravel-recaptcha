<?php
/**
 * @author      Peter Fox <peter.fox@peterfox.me>
 * @copyright   Peter Fox 2015
 *
 * @package     ${PROJECT_NAME}
 */

namespace spec\RecaptchaLaravel\Core;

use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response;
use RecaptchaLaravel\Core\RecaptchaClient;

/**
 * Class RecaptchaClientSpec
 * @package spec\RecaptchaLaravel\Core
 * @mixin RecaptchaClient
 */
class RecaptchaClientSpec extends ObjectBehavior
{
    function let(ReCaptcha $reCaptcha, Request $request, LoggerInterface $logger)
    {
        $this->beConstructedWith('testKey', $reCaptcha, $request, $logger);
    }

    function it_creates_a_html_field()
    {
        $html = '<div class="g-recaptcha" data-sitekey="testKey"></div>';

        $this->field()->shouldReturn($html);
    }

    function it_should_handle_successful_validations(ReCaptcha $reCaptcha, Request $request, Response $response)
    {
        $request->getClientIp()->willReturn('127.0.0.1');
        $reCaptcha->verify('abc', '127.0.0.1')->willReturn($response);
        $response->isSuccess()->willReturn(true);

        $this->validate('abc')->shouldReturn(true);
    }

    function it_should_handle_unsuccessful_validations
    (
        ReCaptcha $reCaptcha,
        Request $request,
        Response $response,
        LoggerInterface $logger
    )
    {
        $request->getClientIp()->willReturn('127.0.0.1');
        $reCaptcha->verify('abc', '127.0.0.1')->willReturn($response);

        $response->isSuccess()->willReturn(false);

        $response->getErrorCodes()->willReturn(['fail']);

        $logger->debug('Client 127.0.0.1 failed Recaptcha validation with error: fail')->shouldBeCalled();

        $this->validate('abc')->shouldReturn(false);
    }
}