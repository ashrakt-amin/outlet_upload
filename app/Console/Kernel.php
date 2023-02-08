<?php

namespace App\Console;

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
        $schedule->command('start:lastWeekOffers')->thursdays()->at('14:47');
        $schedule->command('start:flash_sales')->wednesdays()->at('15:59');
        // $schedule->command('start:flash_sales')->dailyAt('3:03 PM');
        // $schedule->command('start:flash_sales')->daily();
        $schedule->command('start:flash_sales')->everyMinute();
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
