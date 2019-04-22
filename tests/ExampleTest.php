<?php

namespace Thinkstudeo\Textlocal\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinkstudeo\Textlocal\Facades\TransactionalClient;

class ExampleTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function a_test_where()
    {
        // dd(env('TEXTLOCAL_TRANSACTIONAL_KEY'));
        // dd(TransactionalClient::)
        dd(TransactionalClient::version());
    }
}
