<?php

namespace DhurghamMiswag\Comments;

use DhurghamMiswag\Comments\Livewire\Comments;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CommentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (file_exists(__DIR__.'/../config/comments.php')) {
            $this->mergeConfigFrom(
                __DIR__.'/../config/comments.php', 'comments'
            );
        }
    }

    public function boot()
    {
        Livewire::component('comments', Comments::class);

        $this->publishes([
            __DIR__.'/../config/comments.php' => config_path('comments.php'),
        ], 'comments-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'comments-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/comments'),
        ], 'comments-views');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'comments');
    }
}
