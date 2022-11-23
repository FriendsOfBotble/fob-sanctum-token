<?php

return [
    [
        'name' => 'Sanctum Token',
        'flag' => 'sanctum-token.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'sanctum-token.create',
        'parent_flag' => 'sanctum-token.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'sanctum-token.destroy',
        'parent_flag' => 'sanctum-token.index',
    ],
];
