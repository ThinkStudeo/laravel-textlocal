<?php

namespace Thinkstudeo\Textlocal;

use Thinkstudeo\Textlocal\Traits\HandlesAccount;
use Thinkstudeo\Textlocal\Traits\ManagesHistory;
use Thinkstudeo\Textlocal\Traits\ManagesSurveys;
use Thinkstudeo\Textlocal\Traits\ManagesContacts;
use Thinkstudeo\Textlocal\Traits\ManagesMessages;

class AccountClient
{
    use ManagesMessages,
        ManagesSurveys,
        ManagesContacts,
        ManagesHistory,
        HandlesAccount;
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

    /**
     * Request params to be sent to Textlocal Api
     *
     * @var array
     */
    protected $params = [];

    public function __construct(Request $request)
    {
        // $apiKey = config('services.textlocal.transactional.apiKey');
        // if (empty($apiKey))
        // {
        //     throw AuthenticationException::apiKeyMissing('transactional');
        // }

        // $this->apiKey  = $apiKey;
        $this->request = $request;
        // $this->params  = [];
        // $this->addParam('apiKey', urlencode($apiKey));
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
     * @param $uriKey
     * @return string
     */
    protected function requestUrl($uriKey)
    {
        return self::BASE_URL . $uriKey . '/';
    }

    /**
     * Add a parameter for the form data to be sent with api request.
     * @param $key
     * @param $value
     */
    protected function addParam($key, $value)
    {
        if ($value)
        {
            $this->params[$key] = $value;
        }
    }
}
