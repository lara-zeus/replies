<?php

return [
    /**
     * set the database tables prefix
     */
    'table-prefix' => 'replies_',

    /**
     * you can overwrite any model and use your own
     * you can also configure the model per panel in your panel provider
     * using: ->models([ ... ])
     */
    'models' => [
        'User' => config('auth.providers.users.model'),
        'Comment' => \LaraZeus\Replies\Models\Comment::class,
    ],

    /**
     * Determines if replies will be deleted when comments are deleted
     */
    'delete_replies_along_comments' => false,

    /**
     * set the comments editor toolbar
     */
    'comments-editor-toolbar' => [
        'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        'codeBlock',
        'heading',
        'italic',
        'link',
        'orderedList',
        'redo',
        'strike',
        'table',
        'undo',
    ],

    'chat_polling' => [
        'enabled' => true,
        'time' => '15s', // or '15000ms' or 'keep-alive'
    ],
];
