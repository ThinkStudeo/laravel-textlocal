<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\Response;
use Thinkstudeo\Textlocal\Exceptions\InvalidInput;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait ManagesSurveys
{
    /**
     * Fetch a list of all surveys associated with the account.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function surveys()
    {
        $response = $this->request->post($this->requestUrl('get_surveys'), $this->params);

        return $response;
    }

    /**
     * Fetch the details for the survey identified by the surveyId.
     *
     * @param number $surveyId
     * @return Response
     * @throws ApiRequestFailure|InvalidInput
     */
    public function surveyDetails($surveyId)
    {
        if (!is_numeric($surveyId))
        {
            throw InvalidInput::unacceptable('The Survey Id must be a valid positive number', 'INVALID SURVEY ID');
        }

        $this->addParam('survey_id', $surveyId);

        $response = $this->request->post($this->requestUrl('get_survey_details'), $this->params);

        return $response;
    }

    /**
     * Fetch the results for the survey identified by the surveyId.
     *
     * @param number $surveyId
     * @param number|null $startDate
     * @param number|null $endDate
     * @return Response
     * @throws ApiRequestFailure|InvalidInput
     */
    public function surveyResults($surveyId, $startDate = null, $endDate = null)
    {
        if (!is_numeric($surveyId))
        {
            throw InvalidInput::unacceptable('The Survey Id must be a valid positive number', 'INVALID SURVEY ID');
        }

        $this->addParam('survey_id', $surveyId);

        $this->setStartDate($startDate);
        $this->setEndDate($endDate);

        $response = $this->request->post($this->requestUrl('get_survey_results'), $this->params);

        return $response;
    }

    /**
     * The start date from when the survey details are to be fetched.
     *
     * @param number|null $time
     * @return void
     * @throws InvalidInput
     */
    protected function setStartDate($time)
    {
        if (!is_null($time))
        {
            if (!is_numeric($time))
            {
                throw InvalidInput::unacceptable("'StartDate' value should be a unix timestamp", 'INVALID TIMESTAMP');
            }

            $this->addParam('start_date', $time);
        }
    }

    /**
     * The end date till which the survey details are to be fetched.
     *
     * @param number|null  $time
     * @return void
     * @throws InvalidInput
     */
    protected function setEndDate($time)
    {
        if (!is_null($time))
        {
            if (!is_numeric($time))
            {
                throw InvalidInput::unacceptable("'EndDate' value should be a unix timestamp", 'INVALID TIMESTAMP');
            }

            $this->addParam('end_date', $time);
        }
    }
}