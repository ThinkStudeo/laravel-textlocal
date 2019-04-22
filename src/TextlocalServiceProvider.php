<?php

namespace Thinkstudeo\Textlocal;

use Illuminate\Support\ServiceProvider;

class TextlocalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() : void
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->bind('messageClient', 'Thinkstudeo\Textlocal\MessageClient');
        $this->app->bind('accountClient', 'Thinkstudeo\Textlocal\AccountClient');
    }
}
