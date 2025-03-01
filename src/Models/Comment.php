<?php

namespace LaraZeus\Replies\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LaraZeus\Replies\Events\CommentAdded;
use LaraZeus\Replies\Events\CommentDeleted;
use LaraZeus\Replies\Traits\HasComments;

class Comment extends Model
{
    use HasComments;

    protected $fillable = [
        'comment',
        'user_id',
        'is_approved',
        'read_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $model) {
            if (config('zeus-replies.delete_replies_along_comments')) {
                $model->comments()->delete();
            }
        });

        static::deleted(function (self $model) {
            CommentDeleted::dispatch($model);
        });

        static::created(function (self $model) {
            CommentAdded::dispatch($model);
        });
    }

    /**
     * @param  Builder<Comment>  $query
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function commentator(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('zeus-replies.models.User'), 'user_id');
    }

    public function approve(): static
    {
        $this->update([
            'is_approved' => true,
        ]);

        return $this;
    }

    public function disapprove(): static
    {
        $this->update([
            'is_approved' => false,
        ]);

        return $this;
    }
}
