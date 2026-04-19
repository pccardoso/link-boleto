<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('boletos:prepare-open')
    ->dailyAt('22:00');

Schedule::command('app:sync-bills-status')
    ->dailyAt('01:00');

Schedule::command('app:send-emails')
    ->dailyAt('07:00');