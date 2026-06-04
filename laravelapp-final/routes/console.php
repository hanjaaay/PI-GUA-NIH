<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bookings:expire')
    ->everyFiveMinutes();

// Schedule::command('backup:database')
//    ->daily()
//    ->at('00:00')
//    ->appendOutputTo(storage_path('logs/backup.log'));

// Schedule::command('backup:files')
//    ->weekly()
//    ->sundays()
//    ->at('00:00')
//    ->appendOutputTo(storage_path('logs/backup.log'));
