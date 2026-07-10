<?php

return [
    'model' => 'App\\Models\\User',

    'roles_table' => 'roles',
    'role_user_table' => 'role_user',
    'permissions_table' => 'permissions',
    'permission_role_table' => 'permission_role',
    'permission_user_table' => 'permission_user',

    'use_teams' => false,
    'teams_table' => 'teams',
    'team_role_table' => 'team_role',
    'team_permission_table' => 'team_permission',
];
