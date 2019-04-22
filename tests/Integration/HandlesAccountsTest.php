<?php

namespace Thinkstudeo\Textlocal\Tests\Integration;

use Thinkstudeo\Textlocal\Tests\TestCase;
use Thinkstudeo\Textlocal\Facades\Account;

class HandlesAccountsTest extends TestCase
{
    /** @test */
    public function it_can_fetch_all_templates_associated_with_the_account()
    {
        $response = Account::promotional()->templates();

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_fetch_all_the_sender_names_associated_with_the_account()
    {
        $response = Account::promotional()->senders();

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_fetch_the_account_balance()
    {
        $response = Account::promotional()->balance();

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_check_the_availability_of_keyword()
    {
        $response = Account::promotional()->checkKeyword('STUDEO');

        $this->assertEquals('success', $response->status);
    }
}
