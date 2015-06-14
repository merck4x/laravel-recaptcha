<?php

namespace RecaptchaLaravel\Core;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use ReCaptcha\ReCaptcha;

/**
 * Description of RecaptchaClient
 *
 * @author sly
 */
class RecaptchaClient
{
    const API_URI = 'hhttps://www.google.com/recaptcha/api/siteverify';

    protected $client;
    protected $useSsl;

    protected $domain;
    protected $siteKey;
    protected $secret;

    protected $defaultError;
    /**
     * @var ReCaptcha
     */
    private $reCaptcha;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct($siteKey, ReCaptcha $reCaptcha, Request $request, LoggerInterface $logger)
    {
        $this->siteKey = $siteKey;
        $this->reCaptcha = $reCaptcha;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function field()
    {
        return sprintf('<div class="g-recaptcha" data-sitekey="%s"></div>', $this->siteKey);
    }

    public function validate($response)
    {
        $resp = $this->reCaptcha->verify($response, $this->request->getClientIp());
        if ($resp->isSuccess()) {

            return true;
        }

        $errors = $resp->getErrorCodes();

        foreach ($errors as $error) {
            $this->logger->debug(sprintf(
                'Client %s failed Recaptcha validation with error: %s',
                $this->request->getClientIp(), $error));
        }

        return false;
    }
}
