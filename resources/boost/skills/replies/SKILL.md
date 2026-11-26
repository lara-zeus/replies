---
name: zeus-replies
description: Add morph-based comments, approvals, nested replies, and the Livewire comments component using lara-zeus/replies.
---

# Lara Zeus Replies (`lara-zeus/replies`)

## Overview & Requirements
- **Package**: `lara-zeus/replies` (Free / MIT)
- **Requirements**: PHP `^8.1`, `lara-zeus/filament-plugin-tools` `^3.0`
- **Features**: Polymorphic comments on any Eloquent model (including nested replies on `Comment`), approval workflow, `CommentAdded`/`CommentDeleted` events, and a Livewire `replies.comments` component with Filament `MarkdownEditor`.

## Installation
```bash
composer require lara-zeus/replies
php artisan replies:install
```

## Configuration & Migrations
Publish config and migrations individually:
```bash
php artisan vendor:publish --tag=zeus-replies-config
php artisan vendor:publish --tag=zeus-replies-migrations
php artisan migrate
```
Config file `config/zeus-replies.php`:
- `table-prefix` (`'replies_'`)
- `models`: `'User' => config('auth.providers.users.model')`, `'Comment' => LaraZeus\Replies\Models\Comment::class`
- `delete_replies_along_comments` (`false`): cascade-delete nested replies when deleting a `Comment`
- `comments-editor-toolbar`: array of Filament `MarkdownEditor` toolbar buttons
- `chat_polling`: `['enabled' => true, 'time' => '15s']`

> **Migration note**: `database/migrations/create_comments_table.php.stub` uses `config('zeus-replies.table-prefix').'comments'`, whereas `RepliesServiceProvider::configurePackage()` references `'create_bookmarks_table'`. Verify the published migration and `Comment` model table name (`$table` is not overridden in `LaraZeus\Replies\Models\Comment`) match before migrating.

## Filament Panel Registration
Register `RepliesPlugin` in your `PanelProvider`:
```php
use LaraZeus\Replies\RepliesPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            RepliesPlugin::make()
                ->models([
                    'User' => \App\Models\User::class,
                    'Comment' => \LaraZeus\Replies\Models\Comment::class,
                ]),
        ]);
}
```
Include `./vendor/lara-zeus/replies/resources/views/**/*.blade.php` in your Filament theme's `tailwind.config.js` `content` array.

## Core Usage & Examples

### 1. Make a Model Commentable
```php
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Replies\Traits\HasComments;

class Post extends Model
{
    use HasComments;
}

// Create comments
$comment = $post->comment('Great post!');
$comment = $post->commentAsUser($user, 'Comment on behalf of user');

// Nested reply (Comment also uses HasComments)
$reply = $comment->comment('Nested reply');

// Query approved comments
$approved = $post->comments()->approved()->get();
```

### 2. Auto-Approval via `Commentator`
```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use LaraZeus\Replies\Contracts\Commentator;
use LaraZeus\Replies\Traits\CanComment;

class User extends Authenticatable implements Commentator
{
    use CanComment;

    public function needsCommentApproval(mixed $model): bool
    {
        return false; // auto-approve comments
    }
}
```
Manual approval: `$comment->approve();` or `$comment->disapprove();`.

### 3. Render the Livewire Component
```blade
<livewire:replies.comments :item="$ticket" />
```

### 4. Events
Listen to `LaraZeus\Replies\Events\CommentAdded` and `LaraZeus\Replies\Events\CommentDeleted` (both implement `ShouldDispatchAfterCommit` and expose `$event->comment`).

## Common Pitfalls & Anti-Patterns
- **Unauthenticated Livewire render**: `resources/views/replies.blade.php` accesses `auth()->user()->id`; only render `<livewire:replies.comments />` for authenticated users.
- **Missing `Commentator` contract**: Using `CanComment` without `implements Commentator` will NOT trigger auto-approval checks in `HasComments::commentAsUser()`.
- **Cascading nested deletes**: Nested replies are only deleted with their parent comment when `config('zeus-replies.delete_replies_along_comments')` is `true`.
