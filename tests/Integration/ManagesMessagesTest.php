<?php

namespace Thinkstudeo\Textlocal\Tests\Integration;

use Thinkstudeo\Textlocal\Facades\Sms;
use Thinkstudeo\Textlocal\Tests\TestCase;
use Thinkstudeo\Textlocal\Facades\Account;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

class ManagesMessagesTest extends TestCase
{
    protected function setUp() :void
    {
        parent::setUp();

        $this->validNumber   = '919033100026';
        $this->dndNumber     = '919879612326';
        $this->invalidNumber = '9112345';
        $this->numbers       = ['919033100026', '919033000026'];
        $this->sender        = urlencode('TXTLCL');
        $this->message       = 'Textlocal trial promotional message.';
    }

    /** @test */
    public function it_can_fetch_the_status_details_of_a_message_via_its_id()
    {
        $response = Sms::promotional()
                        ->to($this->validNumber)
                        ->from($this->sender)
                        ->send($this->message);
        $msg = $response->messages[0];
        sleep(10);
        $msgStatus = Account::promotional()->messageStatus($msg->id);

        $this->assertEquals('success', $msgStatus->status);
    }

    /** @test */
    public function it_throws_an_exception_when_invalid_message_id_is_provided_to_get_the_message_status()
    {
        $this->expectException(ApiRequestFailure::class);

        $response = Sms::promotional()->to($this->validNumber)
                           ->from($this->sender)
                           ->test($this->message);
        $msg = $response->messages[0];
//        sleep(10);
        $msgStatus = Account::promotional()->messageStatus($msg->id);
    }

    /** @test */
    public function it_can_fetch_the_status_details_of_a_message_via_the_batch_id()
    {
        $response = Sms::promotional()
                        ->to($this->validNumber)
                        ->from($this->sender)
                        ->send($this->message);

        $batchId = $response->batch_id;

        sleep(10);
        $msgStatus = Account::promotional()->batchStatus($batchId);

        $this->assertEquals('success', $msgStatus->status);
    }

    /** @test */
    public function it_throws_an_exception_when_an_invalid_batch_id_is_provided_to_fetch_the_status()
    {
        $this->expectException(ApiRequestFailure::class);

        $response = Sms::promotional()
                        ->to($this->validNumber)
                        ->from($this->sender)
                        ->test($this->message);

        $batchId = $response->batch_id;

        Account::promotional()->batchStatus($batchId);
    }

    /** @test */
    public function it_can_fetch_a_list_of_all_inboxes()
    {
        $inboxList = Account::promotional()->inboxes();

        $this->assertEquals('success', $inboxList->status);
    }

    /** @test */
    public function it_throws_an_exception_while_fetching_messages_when_there_are_no_messages_in_the_inbox()
    {
        $this->expectException(ApiRequestFailure::class);

        $inboxList = Account::promotional()->inboxes();
        $inboxId   = $inboxList->inboxes[0]->id;

        Account::promotional()->messages($inboxId);
    }

    //TODO:Run this test when there are actual messages in an inbox.
    /** @test */
//    public function it_can_fetch_all_messages_for_an_inbox_via_its_id ()
//    {
//
//
//        $inboxList = Account::promotional()->inboxes();
//        $inboxId = $inboxList->inboxes[0]->id;
//
//        $messages = Account::promotional()->messages($inboxId);
//
//        $this->assertEquals('success', $messages->status);
//
//    }

    /** @test */
    public function it_can_get_a_list_of_all_scheduled_messages()
    {
        //Arrange
        date_default_timezone_set('GMT');
        $schTime = strtotime('2019-04-30 10:30:00');
        Sms::promotional()->to($this->validNumber)->from($this->sender)->at($schTime)->send($this->message);

        //Act
        $response = Account::promotional()->scheduledMessages();

        //Assert
        $this->assertEquals('success', $response->status);
        $this->assertCount(1, $response->scheduled);

        //Cleanup
        Account::promotional()->cancel($response->scheduled[0]->id);
    }

    /** @test */
    public function it_can_cancel_a_scheduled_message()
    {
        //Arrange
        date_default_timezone_set('GMT');
        $schTime = strtotime('2019-04-30 10:30:00');
        Sms::promotional()->to($this->validNumber)->from($this->sender)->at($schTime)->send($this->message);
        $response = Account::promotional()->scheduledMessages();
        $this->assertEquals('success', $response->status);
        $this->assertCount(1, $response->scheduled);

        //Act
        $response =Account::promotional()->cancel($response->scheduled[0]->id);

        //Assert
        $this->assertEquals('success', $response->status);
    }
}
