## Lara Zeus Replies (`lara-zeus/replies`)

Morph-based commenting and nested replies system for Laravel & FilamentPHP with an included Livewire chat-style comments component.

### Key Classes & Traits
- Plugin: `LaraZeus\Replies\RepliesPlugin` (extends `LaraZeus\FilamentPluginTools\FilamentPluginTools`, ID `zeus-replies`, supports `->models([...])`)
- Commentable trait: `LaraZeus\Replies\Traits\HasComments` (`comments()`, `comment(string $comment)`, `commentAsUser(?Model $user, string $comment)`)
- Commentator contract & trait: `LaraZeus\Replies\Contracts\Commentator` (`needsCommentApproval(mixed $model): bool`), `LaraZeus\Replies\Traits\CanComment`
- Model: `LaraZeus\Replies\Models\Comment` (uses `HasComments` for nested replies, `scopeApproved`, `commentable()`, `commentator()`, `approve()`, `disapprove()`)
- Livewire component: `LaraZeus\Replies\Livewire\Comments` (alias `replies.comments`, takes `:item="$model"`)
- Events: `LaraZeus\Replies\Events\CommentAdded`, `LaraZeus\Replies\Events\CommentDeleted` (both carry `public Comment $comment`)
- Config: `config/zeus-replies.php` (`table-prefix`, `models`, `delete_replies_along_comments`, `comments-editor-toolbar`, `chat_polling`)

### Rules & Pitfalls
- Always implement `Commentator` on your User model (or use `CanComment`) if you want `needsCommentApproval($model)` checked; users not implementing `Commentator` create unapproved (`is_approved = false`) comments.
- Do not use `RepliesServiceProvider`'s `zeus-replies-migrations` tag blindly without verifying: `RepliesServiceProvider` currently lists `create_bookmarks_table` instead of the actual stub `database/migrations/create_comments_table.php.stub`, and `Comment` does not set `$table` from `zeus-replies.table-prefix` (`replies_`)—align your migration table name and `Comment` subclass if using a custom prefix.
- The `replies.blade.php` view accesses `auth()->user()->id` directly and renders `<x-zeus::user-card>`; ensure users are authenticated when rendering `<livewire:replies.comments :item="$model" />`.
