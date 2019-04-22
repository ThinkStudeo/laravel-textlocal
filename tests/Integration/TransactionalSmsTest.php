<?php

namespace Thinkstudeo\Textlocal\Tests\Integration;

use Thinkstudeo\Textlocal\Facades\Sms;
use Thinkstudeo\Textlocal\Tests\TestCase;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

class TransactionalSmsTest extends TestCase
{
    protected function setUp() :void
    {
        parent::setUp();

        $this->validNumber   = '919033100026';
        $this->dndNumber     = '919879612326';
        $this->invalidNumber = '9112345';
        $this->numbers       = ['919033100026', '919033000026'];
        $this->sender        = urlencode('STUDEO');
        $this->template      = 'Your OTP for Thinkstudeo is %s. It is valid for the next 10 minutes only.';
    }

    /** @test */
    public function it_throws_an_exception_when_sms_is_sent_to_invalid_number()
    {
        $this->expectException(ApiRequestFailure::class);

        Sms::transactional()
            ->to($this->invalidNumber)
            ->from($this->sender)
            ->test(sprintf($this->template, '335867'));
    }

    /** @test */
    public function it_sends_sms_to_a_single_number()
    {
        $response = Sms::transactional()
                        ->to($this->validNumber)
                        ->from($this->sender)
                        ->test(sprintf($this->template, '335867'));

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_send_sms_to_multiple_numbers()
    {
        $response = Sms::transactional()
                        ->to($this->numbers)
                        ->from($this->sender)
                        ->test(sprintf($this->template, '335867'));

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_thows_an_exception_when_sms_to_group_request_is_sent_with_invalid_group_name()
    {
        $this->expectException(ApiRequestFailure::class);

        Sms::transactional()
            ->toGroup('Invalid')
            ->from($this->sender)
            ->test(sprintf($this->template, '337590'));
    }

    /** @test */
    public function it_throws_an_exception_when_sms_to_group_request_is_sent_to_a_group_without_any_members()
    {
        $this->expectException(ApiRequestFailure::class);

        Sms::transactional()
            ->toGroup(946409)
            ->from($this->sender)
            ->test(sprintf($this->template, '337590'));
    }
}
