<?php

namespace Herd\Comments;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Herd\Comments\Livewire\Comments;

class CommentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Only merge config if the file exists
        if (file_exists(__DIR__.'/../config/comments.php')) {
            $this->mergeConfigFrom(
                __DIR__.'/../config/comments.php', 'comments'
            );
        }
    }

    public function boot()
    {
        // Register Livewire component
        Livewire::component('comments', Comments::class);

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/comments.php' => config_path('comments.php'),
        ], 'comments-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'comments-migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/comments'),
        ], 'comments-views');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'comments');
    }
} 