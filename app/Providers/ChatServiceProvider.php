<?php

namespace App\Providers;

use App\Services\ChatService;
use App\Services\Interfaces\ChatServiceInterface;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ChatServiceInterface::class,
            ChatService::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
