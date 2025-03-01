<?php

namespace LaraZeus\Replies\Traits;

trait CanComment
{
    public function needsCommentApproval($model): bool
    {
        return true;
    }
}
