<?php

namespace LaraZeus\Replies\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use LaraZeus\Replies\Contracts\Commentator;
use LaraZeus\Replies\Models\Comment;

/**
 * @template TModel of Model
 */
trait HasComments
{
    public function comments(): MorphMany
    {
        // @phpstan-ignore-next-line
        return $this->morphMany(config('zeus-replies.models.Comment'), 'commentable');
    }

    public function comment(string $comment): Model
    {
        return $this->commentAsUser(auth()->user(), $comment);
    }

    public function commentAsUser(?Model $user, string $comment): Comment
    {
        // @phpstan-ignore-next-line
        return $this->comments()->create([
            'comment' => $comment,
            'is_approved' => $user instanceof Commentator && ! $user->needsCommentApproval($this),
            'user_id' => is_null($user) ? null : $user->getKey(),
            'commentable_id' => $this->getKey(),
            'commentable_type' => get_class($this),
        ]);
    }
}
