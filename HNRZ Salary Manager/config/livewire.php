<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'asset_url' => null,
    'middleware' => ['web'],
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => null,
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => ['png', 'jpg', 'jpeg', 'gif', 'svg'],
        'max_upload_time' => 5,
    ],
];
