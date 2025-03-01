---
title: Configuration
weight: 3
---

## Configuration

to configure the plugin Replies, you can pass the configuration to the plugin in `adminPanelProvider`

these all the available configuration, and their defaults values

```php
RepliesPlugin::make()
    ->models([
        'User' => config('auth.providers.users.model'),
        'Comment' => \LaraZeus\Replies\Models\Comment::class,
    ])
```

## Configuration File

use the file `zeu-replies.php`, to customize the global configuration.

to publish the configuration:

```bash
php artisan vendor:publish --tag=zeus-replies-config
```
