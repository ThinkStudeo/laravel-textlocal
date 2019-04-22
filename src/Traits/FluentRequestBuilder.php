<?php

namespace Thinkstudeo\Textlocal\Traits;

use Illuminate\Support\Carbon;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait FluentRequestBuilder
{
    /**
     * @var array
     */
    protected $params = [];

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

    /**
     * Set the time in future at which the message should be sent.
     * @param string|number $schedule
     * @return TransactionalClient|PromotionalClient|FluentRequestBuilder
     */
    public function at($schedule)
    {
        $time = is_numeric($schedule) ? $schedule : Carbon::parse($time)->timestamp;
        $this->addParam('schedule_time', $time);

        return $this;
    }

    /**
     * Set the sender string for the message.
     * @param $sender
     * @return TransactionalClient|PromotionalClient|FluentRequestBuilder
     */
    public function from($sender)
    {
        $this->addParam('sender', $sender);

        return $this;
    }

    /**
     * Prepare comma separated list of numbers to which the message is to be sent.
     * @param $numbers
     * @return TransactionalClient|PromotionalClient|FluentRequestBuilder
     */
    public function to($numbers)
    {
        $this->addParam(
            'numbers',
            implode(
                ',',
                is_string($numbers) ? explode(',', $numbers) : $numbers
            )
        );

        return $this;
    }

    /**
     * Attach a file to be sent with the sms message.
     * @param $path
     * @return TransactionalClient|PromotionalClient|FluentRequestBuilder
     * @throws ApiRequestFailure
     */
    public function attach($path)
    {
//        var_dump("SmsClient attach path: $path");
//        var_dump(is_readable("~/Pictures/ui-example.gif"));

        if (!$this->isReadableFile($path) && !$this->isUrl($path))
        {
            throw ApiRequestFailure::requestRespondedWithError('Invalid attachment file/url', 'INVATCH');
        }

        return $this;
    }

    /**
     * Send the message to all the contacts in the group.
     * @param $group
     * @return TransactionalClient|PromotionalClient|FluentRequestBuilder
     */
    public function toGroup($group)
    {
        $this->addParam('group_id', is_numeric($group) ? $group : $this->getGroupId($group));

        return $this;
    }
}
