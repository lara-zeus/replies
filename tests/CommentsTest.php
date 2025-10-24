<?php

// uses(\LaraZeus\Replies\Tests\TestCase::class);
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Event;
use LaraZeus\Replies\Events\CommentAdded;
use LaraZeus\Replies\Events\CommentDeleted;
use LaraZeus\Replies\Tests\Models\ApprovedUser;
use LaraZeus\Replies\Tests\Models\Post;

test('users without commentator interface do not get approved', function () {
    $post = Post::create([
        'title' => 'Some post',
    ]);

    $post->comment('this is a comment');

    $comment = $post->comments()->first();

    expect($comment->is_approved)->toBeFalse();
});

test('models can store comments', function () {
    $post = Post::create([
        'title' => 'Some post',
    ]);

    $post->comment('this is a comment');
    $post->comment('this is a different comment');

    expect($post->comments)->toHaveCount(2)
        ->and($post->comments[0]->comment)->toBe('this is a comment')
        ->and($post->comments[1]->comment)->toBe('this is a different comment');

});

test('comments can be posted as authenticated users', function () {
    $user = User::first();

    auth()->login($user);

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this is a comment');

    expect($comment->commentator->toArray())->toBe($user->toArray());
});

test('comments can be posted as different users', function () {
    $user = User::first();

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->commentAsUser($user, 'this is a comment');

    expect($comment->commentator->toArray())->toBe($user->toArray());
});

test('comments can be approved', function () {
    $user = User::first();

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this is a comment');

    expect($comment->is_approved)->toBeFalse();

    $comment->approve();

    expect($comment->is_approved)->toBeTrue();
});

test('comments resolve the commented model', function () {
    $user = User::first();

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this is a comment');

    expect($post->id)->toBe($comment->commentable->id);
    expect($post->title)->toBe($comment->commentable->title);
});

test('users can be auto approved', function () {
    $user = ApprovedUser::first();

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->commentAsUser($user, 'this is a comment');

    expect($comment->is_approved)->toBeTrue();
});

test('comments have an approved scope', function () {
    $user = ApprovedUser::first();

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $post->comment('this comment is not approved');
    $post->commentAsUser($user, 'this comment is approved');

    expect($post->comments)->toHaveCount(2);
    expect($post->comments()->approved()->get())->toHaveCount(1);

    expect($post->comments()->approved()->first()->comment)->toBe('this comment is approved');
});

test('comments are deleted when posts are deleted', function () {
    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this comment will be deleted');

    $post->delete();

    expect($comment->exists())->toBeFalse();
});

test('replies are deleted when post comments are deleted', function () {
    config(['zeus-replies.delete_replies_along_comments' => true]);

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this comment will be deleted');
    $reply = $comment->comment('this comment will be deleted too');

    $comment->delete();

    expect($reply->exists())->toBeFalse();
});

test('comment added event is dispatched when comment is created', function () {
    Event::fake([CommentAdded::class]);

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this comment is added');

    Event::assertDispatched(CommentAdded::class, function ($event) use ($comment) {
        return $event->comment->is($comment);
    });
});

test('comment deleted event is dispatched when comment is deleted', function () {
    Event::fake([CommentDeleted::class]);

    $post = Post::create([
        'title' => 'Some post',
    ]);

    $comment = $post->comment('this comment is added');

    $comment->delete();

    Event::assertDispatched(CommentDeleted::class, function ($event) use ($comment) {
        return $event->comment->is($comment);
    });
});
