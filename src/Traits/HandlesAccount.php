<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\Response;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait HandlesAccount
{
    /**
     * Fetch all templates from the account.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function templates()
    {
        $response = $this->request->post($this->requestUrl('get_templates'), $this->params);

        return $response;
    }

    /**
     * Get a list of senders associated with the account.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function senders()
    {
        return $this->request->post($this->requestUrl('get_sender_names'), $this->params);
    }

    /**
     * Get the balance associated with the account.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function balance()
    {
        return $this->request->post($this->requestUrl('balance'), $this->params);
    }

    /**
     * Determine whether the keyword is available for the account.
     *
     * @param string $word
     * @return Response
     * @throws ApiRequestFailure
     */
    public function checkKeyword($word)
    {
        $this->addParam('keyword', $word);

        return $this->request->post($this->requestUrl('check_keyword'), $this->params);
    }
}