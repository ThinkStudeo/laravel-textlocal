<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\Response;
use Thinkstudeo\Textlocal\Exceptions\InvalidInput;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait ManagesHistory
{
    /**
     * Fetch the history records from the account.
     *
     * @param string $mode
     * @param number|null $since
     * @param number|null $upto
     * @param int $start
     * @param int $limit
     * @param string $order
     * @return Response
     * @throws ApiRequestFailure
     */
    public function history($mode = 'single', $since = null, $upto = null, $start = 0, $limit = 1000, $order = 'desc')
    {
        $this->historyUri($mode);
        $this->setSince($since);
        $this->setUpto($upto);
        $this->setStart($start);
        $this->setLimit($limit);
        $this->setOrder($order);

        $response = $this->request->post($this->requestUrl($this->historyUri($mode)), $this->params);

        return $response;
    }

    /**
     * The uriKey for the api history request.
     *
     * @param string $mode
     * @return string
     * @throws InvalidInput
     */
    protected function historyUri($mode)
    {
        if (!in_array($mode, ['single', 'group', 'api']))
        {
            throw InvalidInput::unacceptable("'Mode' value should be 'single', 'group', 'api'", 'INVALID HISTORY MODE');
        }

        return "get_history_{$mode}";
    }

    /**
     * Set since (min_time) which time to get the history records from the account.
     *
     * @param number $time
     * @return void
     * @throws InvalidInput
     */
    protected function setSince($time)
    {
        if (!is_null($time))
        {
            if (!is_numeric($time))
            {
                throw InvalidInput::unacceptable("'Since' value should be a unix timestamp", 'INVALID TIMESTAMP');
            }

            $this->addParam('min_time', $time);
        }
    }

    /**
     * Set upto (max_time) which time to get the history records from the account.
     *
     * @param number $time
     * @return void
     * @throws InvalidInput
     */
    protected function setUpto($time)
    {
        if (!is_null($time))
        {
            if (!is_numeric($time))
            {
                throw InvalidInput::unacceptable("'Upto' value should be a unix timestamp", 'INVALID TIMESTAMP');
            }

            $this->addParam('max_time', $time);
        }
    }

    /**
     * The offset from which to retrieve the history records from the account.
     *
     * @param number $offset
     * @return void
     * @throws InvalidInput
     */
    protected function setStart($offset)
    {
        if (!is_numeric($offset))
        {
            throw InvalidInput::unacceptable("'Start' value should be a valid positive number", 'INVALID NUMBER');
        }
        if ($offset > 0)
        {
            $this->addParam('start', $offset);
        }
    }

    /**
     * The limit in number of history records to be retrieved from the account.
     *
     * @param number $limit
     * @return void
     * @throws InvalidInput
     */
    protected function setLimit($limit)
    {
        if (!is_numeric($limit) && $limit <= 0)
        {
            throw InvalidInput::unacceptable("'Limit' value should be a valid positive number", 'INVALID NUMBER');
        }
        if ($limit !== 1000)
        {
            $this->addParam('limit', $limit);
        }
    }

    /**
     * The order in which the history records should be retrieved from the account.
     *
     * @param string $order
     * @return void
     * @throws InvalidInput
     */
    protected function setOrder($order)
    {
        if (!in_array($order, ['desc', 'asc']))
        {
            throw InvalidInput::unacceptable("'Order' value should be wither 'asc' or 'desc'", 'INVALID ORDER');
        }
        if ($order !== 'desc')
        {
            $this->addParam('order', $order);
        }
    }
}
