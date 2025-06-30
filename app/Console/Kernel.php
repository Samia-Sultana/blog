<?php

namespace App\Console;

use App\Jobs\WeeklyBackupJob;
use App\Services\Constant\TimeZoneService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule
            ->command('sitemap:generate')
            ->daily()
            ->at('00:00')
            ->timezone(TimeZoneService::BD_TIME_ZONE)
            ->withoutOverlapping()
            ->onOneServer()
            ->appendOutputTo(storage_path('logs/sitemap.log'));

        $schedule->job(new WeeklyBackupJob)->daily();
        $schedule->command('clean:exports')
            ->weeklyOn(0, '00:00')
            ->timezone('Your/Timezone');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
