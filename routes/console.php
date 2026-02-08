<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler: Auto-hapus foto galeri setelah 24 jam
|--------------------------------------------------------------------------
| Jalankan: php artisan schedule:work (development)
| Production: Tambahkan ke crontab: * * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1
*/
Schedule::command('gallery:cleanup-expired')->hourly();
