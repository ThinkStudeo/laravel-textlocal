<?php

namespace Thinkstudeo\Textlocal;

use Thinkstudeo\Textlocal\Traits\HasOptions;
use Thinkstudeo\Textlocal\Traits\ManagesContacts;
use Thinkstudeo\Textlocal\Traits\FluentRequestBuilder;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;
use Thinkstudeo\Textlocal\Exceptions\AuthenticationException;

class MessageClient
{
    use FluentRequestBuilder,
        HasOptions,
        ManagesContacts;

    /**
     * Base api url
     */
    const BASE_URL = 'https://api.textlocal.in/';

    /**
     * Textlocal API Key for transactional account
     *
     * @var string
     * @throws AuthenticationException
     */
    protected $apiKey;

    public function __construct(Request $request)
    {
        // $apiKey = config('services.textlocal.transactional.apiKey');
        // if (empty($apiKey))
        // {
        //     throw AuthenticationException::apiKeyMissing('transactional');
        // }
        // $this->addParam('apiKey', urlencode($apiKey));
        // $this->apiKey  = $apiKey;
        $this->request = $request;
    }

    /**
     * Get the version of the Package class.
     *
     * @return string
     */
    public function version()
    {
        return '1.0.0';
    }

    /**
     * Set the Account to be used as Transactional Account.
     *
     * @return MessageClient
     */
    public function transactional()
    {
        $apiKey = config('services.textlocal.transactional.apiKey');
        if (empty($apiKey))
        {
            throw AuthenticationException::apiKeyMissing('transactional');
        }
        $this->addParam('apiKey', urlencode($apiKey));

        return $this;
    }

    /**
     * Set the Account to be used as Promotional Account.
     *
     * @return MessageClient
     */
    public function promotional()
    {
        $apiKey = config('services.textlocal.promotional.apiKey');
        if (empty($apiKey))
        {
            throw AuthenticationException::apiKeyMissing('promotional');
        }
        $this->addParam('apiKey', urlencode($apiKey));

        return $this;
    }

    /**
     * Send the message as an sms.
     * @param $message
     * @return Response|ApiRequestFailure
     */
    public function send($message = null)
    {
        // dd($this->request->post());

        $this->addParam('message', rawurlencode($message));

        $uriKey   = $this->hasAttachment() ? 'send_mms' : 'send';
        $response = $this->request->post($this->requestUrl($uriKey), $this->params);

        return $response;
    }

    /**
     * @param $message
     * @return Response|ApiRequestFailure
     */
    public function test($message = null)
    {
        $this->addParam('test', true);
        $this->addParam('message', $message);

        return $this->send($message);
    }

    /**
     * @param $uriKey
     * @return string
     */
    protected function requestUrl($uriKey)
    {
        return self::BASE_URL . $uriKey . '/';
    }

    /**
     * Determine if the file provided for attachment is readable
     *
     * @param string $path
     * @return boolean
     */
    protected function isReadableFile($path)
    {
        if (is_readable($path))
        {
            $this->addParam('file', "@{$path}");

            return true;
        }

        return false;
    }

    /**
     * Determine if the provided file path is a url.
     *
     * @param string $path
     * @return boolean
     */
    protected function isUrl($path)
    {
        if (startsWith($path, ['http', 'www']))
        {
            $this->addParam('url', $path);

            return true;
        }

        return false;
    }

    /**
     * Determine whether there is any file attachment.
     *
     * @return boolean
     */
    protected function hasAttachment()
    {
        return isset($this->params['file']) || isset($this->params['url']);
    }
}
