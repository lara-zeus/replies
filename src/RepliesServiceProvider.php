<?php

namespace LaraZeus\Replies;

use LaraZeus\Replies\Console\InstallCommand;
use LaraZeus\Replies\Livewire\Comments;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RepliesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'zeus-replies';

    public function packageBooted(): void
    {
        Livewire::addComponent(name:'replies.comments', class: Comments::class);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews(static::$name)
            ->hasTranslations()
            ->hasConfigFile()
            ->hasMigrations([
                'create_bookmarks_table',
            ])
            ->hasCommands([
                InstallCommand::class,
            ]);
    }
}
