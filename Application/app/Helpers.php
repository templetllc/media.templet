<?php

// Format Bytes for uploads size
function formatBytes($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function getUserRole($user)
{
    $map = array(
        ADMIN_ROLE => __('Admin'),
        USER_ROLE => __('User'),
        CONTRIBUTOR_ROLE => __('Contributor'),
        MANAGER_ROLE => __('Manager'),
        APPROVER_ROLE => __('Approver')
    );
    $key = $user->permission;

    return isset($map[$key]) ? $map[$key] : "";
}

function userHasRole($user_role, $roles_allowed)
{
    return in_array($user_role, $roles_allowed);
}
