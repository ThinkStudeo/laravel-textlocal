<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\Response;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait ManagesMessages
{
    /**
     * Fetch the message status for the given message id.
     *
     * @param number $msgId
     * @return Response
     * @throws ApiRequestFailure
     */
    public function messageStatus($msgId)
    {
        if (!is_numeric($msgId))
        {
            throw ApiRequestFailure::requestRespondedWithError('Invalid message Id', 'INVALID MESSAGE ID');
        }

        $this->addParam('message_id', $msgId);

        $response = $this->request->post($this->requestUrl('status_message'), $this->params);

        return $response;
    }

    /**
     * Fetch the status details for given batch id.
     *
     * @param number $batchId
     * @return Response
     * @throws ApiRequestFailure
     */
    public function batchStatus($batchId)
    {
        if (!is_numeric($batchId))
        {
            throw ApiRequestFailure::requestRespondedWithError('Invalid batchId Id', 'INVALID BATCH ID');
        }

        $this->addParam('batch_id', $batchId);

        $response = $this->request->post($this->requestUrl('status_batch'), $this->params);

        return $response;
    }

    /**
     * Fetch the list of inbox associated with the account.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function inboxes()
    {
        $response = $this->request->post($this->requestUrl('get_inboxes'), $this->params);

        return $response;
    }

    /**
     * Fetch all the messages for the inbox identified by the given id.
     *
     * @param number $inboxId
     * @return Response
     * @throws ApiRequestFailure
     */
    public function messages($inboxId)
    {
        if (!is_numeric($inboxId))
        {
            throw ApiRequestFailure::requestRespondedWithError('Invalid Inbox Id', 'INVALID INBOX ID');
        }

        $this->addParam('inbox_id', $inboxId);

        $response = $this->request->post($this->requestUrl('get_messages'), $this->params);

        return $response;
    }

    /**
     * Fetch the list of all scheduled messages in the acccount.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function scheduledMessages()
    {
        $response = $this->request->post($this->requestUrl('get_scheduled'), $this->params);

        return $response;
    }

    /**
     * Cancel the scheduled messages identified by the given sentId
     *
     * @param number $sentId
     * @return Response
     * @throws ApiRequestFailure
     */
    public function cancel($sentId)
    {
        if (!is_numeric($sentId))
        {
            throw ApiRequestFailure::requestRespondedWithError('Invalid Sent Id', 'INVALID SENT ID');
        }

        $this->addParam('sent_id', $sentId);

        $response = $this->request->post($this->requestUrl('cancel_scheduled'), $this->params);

        return $response;
    }
}
