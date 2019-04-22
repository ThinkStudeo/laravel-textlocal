<?php

namespace Thinkstudeo\Textlocal\Tests\Integration;

use Thinkstudeo\Textlocal\Tests\TestCase;
use Thinkstudeo\Textlocal\Facades\Account;

class ManagesHistoryTest extends TestCase
{
    /** @test */
    public function it_can_fetch_single_message_history()
    {
        //Act
        $response = Account::promotional()->history('single');

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_fetch_group_message_history()
    {
        //Act
        $response = Account::promotional()->history('group');

        $this->assertEquals('success', $response->status);
    }

    /** @test */
    public function it_can_fetch_api_message_history()
    {
        //Act
        $response = Account::promotional()->history('api');

        $this->assertEquals('success', $response->status);
    }
}
