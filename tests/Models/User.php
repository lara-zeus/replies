<?php

namespace LaraZeus\Replies\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $email
 * @property string $name
 * @property string $password
 */
class User extends Authenticatable
{
    protected $guarded = [];

    public $timestamps = false;

    protected $table = 'users';
}
