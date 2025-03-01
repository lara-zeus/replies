<?php

namespace LaraZeus\Replies\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Replies\Traits\HasComments;

class Post extends Model
{
    use HasComments;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $model) {
            $model->comments()->delete();
        });
    }
}
