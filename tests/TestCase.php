<?php

namespace LaraZeus\Replies\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use LaraZeus\Replies\RepliesServiceProvider;
use LaraZeus\Replies\Tests\Models\User;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithMigration]
class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::create(['email' => 'admin@domain.com', 'name' => 'Admin', 'password' => 'password'])
        );
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            RepliesServiceProvider::class,
        ];
    }
}
