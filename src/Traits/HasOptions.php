<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\PromotionalClient;
use Thinkstudeo\Textlocal\TransactionalClient;

trait HasOptions
{
    /**
     * Send the message with Simple Reply Service.
     *
     * @return TransactionalClient|PromotionalClient|HasOptions
     */
    public function withSimpleReplyService()
    {
        $this->srs = true;

        return $this;
    }

    /**
     * Include any custom options or parameters for the request.
     *
     * @param $custom
     * @return TransactionalClient|PromotionalClient|HasOptions
     */
    public function with($custom)
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * Set a receipt uri at which textlocal.in should post back the message receipt.
     *
     * @param $uri
     * @return TransactionalClient|PromotionalClient|HasOptions
     */
    public function receiptUri($uri)
    {
        $this->receiptCallback = $uri;

        return $this;
    }

    /**
     * Include even the ones who have opted out, while sending the message.
     *
     * @return TransactionalClient|PromotionalClient|HasOptions
     */
    public function filterOptouts()
    {
        $this->optOuts = true;

        return $this;
    }
}
