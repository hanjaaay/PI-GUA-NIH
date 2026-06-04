<?php

return [
    

    // Maximum number of backups to keep
    'max_backups' => env('BACKUP_MAX_FILES', 5),

    // Backup schedule (in cron format)
    'schedule' => [
        'database' => env('BACKUP_DATABASE_SCHEDULE', '0 0 * * *'), // Daily at midnight
        'files' => env('BACKUP_FILES_SCHEDULE', '0 0 * * 0'), // Weekly on Sunday
    ],

    // Backup storage disk
    'disk' => env('BACKUP_DISK', 'local'),

    // Backup path
    'path' => env('BACKUP_PATH', 'backups'),

    // Backup compression
    'compression' => [
        'enabled' => true,
        'type' => 'zip',
    ],

    // Backup notification
    'notification' => [
        'enabled' => true,
        'email' => env('BACKUP_NOTIFICATION_EMAIL'),
    ],
];
