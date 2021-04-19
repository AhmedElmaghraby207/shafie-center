<?php

namespace App\Console;

use App\Console\Commands\DrinkWaterNotificationsCron;
use App\Console\Commands\WalkNotificationsCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        WalkNotificationsCron::class,
        DrinkWaterNotificationsCron::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->job(new WalkNotificationBackGround, 'WalkNotificationBackGround')->weeklyOn(1, '10:00'); //monday
        $schedule->command('walk_notification:cron')->everyMinute();

//        $schedule->job(new DrinkWaterNotificationBackGround, 'DrinkWaterNotificationBackGround')->weeklyOn(4, '10:00'); //wednesday
        $schedule->command('drink_water_notification:cron')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
