<?php

namespace LaraZeus\Replies\Tests\Models;

use Illuminate\Foundation\Auth\User;
use LaraZeus\Replies\Contracts\Commentator;

class ApprovedUser extends User implements Commentator
{
    protected $table = 'users';

    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsCommentApproval($model): bool
    {
        return false;
    }
}
