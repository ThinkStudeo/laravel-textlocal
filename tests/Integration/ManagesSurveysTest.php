<?php

namespace Thinkstudeo\Textlocal\Tests\Integration;

use Thinkstudeo\Textlocal\Tests\TestCase;
use Thinkstudeo\Textlocal\Facades\Account;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

class ManagesSurveysTest extends TestCase
{
    /** @test */
    public function it_can_fetch_all_surveys_from_the_account()
    {
        $response = Account::transactional()->surveys();

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_fetch_the_details_of_a_survey_by_its_id()
    {
        //Arrange
        $surveys = Account::transactional()->surveys();

        //Act
        $response = Account::transactional()->surveyDetails($surveys->survey_ids[0]->id);

        //Assert
        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_throws_an_error_while_fetching_results_of_a_survey_if_no_records_are_found()
    {
        $this->expectException(ApiRequestFailure::class);

        $surveys = Account::transactional()->surveys();

        Account::transactional()->surveyResults($surveys->survey_ids[0]->id);
    }

    //TODO:: Uncomment this test and check only after you have some survey with results.
    /** @test */
//    public function it_can_fetch_the_results_of_a_survey_by_its_id ()
//    {
//        //Arrange
//        $surveys = Account::transactional()->surveys();
//
//        //Act
//        $response = Account::transactional()->surveyResults($surveys->survey_ids[0]->id);
//        //Assert
//        $this->assertEquals('success', $response->status);
//    }
}
