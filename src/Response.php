<?php

namespace Thinkstudeo\Textlocal;

use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

class Response
{
    /**
     * Handle the raw response as a result of api request to get the groups.
     * @param $data
     * @return Response
     * @throws ApiRequestFailure
     */
    public function handle($data)
    {
        if ($data->status === 'failure')
        {
            throw ApiRequestFailure::requestRespondedWithError(
                $data->errors[0]->message,
                $data->errors[0]->code
            );
        }

        foreach ($data as $key => $value)
        {
            $this->{$key} = $value;
        }

        return $this;
    }
}
