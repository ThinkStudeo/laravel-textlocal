<?php

namespace Thinkstudeo\Textlocal\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.textlocal.transactional.apiKey', env('TEXTLOCAL_TRANSACTIONAL_KEY'));
        $app['config']->set('services.textlocal.promotional.apiKey', env('TEXTLOCAL_PROMOTIONAL_KEY'));
    }

    protected function getPackageProviders($app)
    {
        return [
            'Thinkstudeo\Textlocal\TextlocalServiceProvider'
        ];
    }
}
