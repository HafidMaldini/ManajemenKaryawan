<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('cuti:reset')->monthly()->at('00:00');
Schedule::command('cuti:update-status')->daily()->at('00:00');
Schedule::command('cleanup:delete-old-records')->daily()->at('00:00');