---
title: Configuration
weight: 3
---

## Configuration

to configure the plugin Replies, you can pass the configuration to the plugin in `adminPanelProvider`

these all the available configuration, and their defaults values

```php
RepliesPlugin::make()
    ->repliesModels([
        'User' => config('auth.providers.users.model'),
        'Bookmark' => \LaraZeus\Replies\Models\Bookmark::class,
    ])
    ->navigationGroupLabel('Replies')
    ->hideResources([
        BookmarkResource::class,
    ])
```

## Configuration File

use the file `zeu-replies.php`, to customize the global configuration.

to publish the configuration:

```bash
php artisan vendor:publish --tag=zeus-replies-config
```

## Render Hooks:

you can customize the render hooks in the config file:

```php
'render-hooks' => [
    'list' => PanelsRenderHook::TOPBAR_END,
    'bookmark_toggle_icon' => TablesRenderHook::TOOLBAR_TOGGLE_COLUMN_TRIGGER_AFTER,
],
```
