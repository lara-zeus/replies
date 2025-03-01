<?php

namespace LaraZeus\Replies;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use LaraZeus\FilamentPluginTools\Concerns\HasModels;
use LaraZeus\FilamentPluginTools\FilamentPluginTools;

final class RepliesPlugin extends FilamentPluginTools implements Plugin
{
    use EvaluatesClosures;
    use HasModels;

    protected string $pluginId = 'zeus-replies';

    // todo get rid of this!
    public static function make(): static
    {
        return new self;
    }

    public function register(Panel $panel): void
    {
        //
    }
}
