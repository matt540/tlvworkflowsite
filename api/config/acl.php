<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    'roles'         => [
        // 'entity' => App\Role::class,
        'entity' => App\Entities\Role::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Available drivers: config|doctrine
    | When set to config, add the permission names to list
    |
    */
    'permissions'   => [
        'driver' => 'doctrine',
        // 'entity' => LaravelDoctrine\ACL\Permissions\Permission::class,
        'entity' => App\Entities\Permission::class,
        // 'list'   => [],
    ],
    /*
    |--------------------------------------------------------------------------
    | Organisations
    |--------------------------------------------------------------------------
    */
    'organisations' => [
        'entity' => App\Organisation::class,
    ],
];
