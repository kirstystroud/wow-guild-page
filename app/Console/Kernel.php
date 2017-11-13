<?php

namespace App\Console;

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
        Commands\GetCharacters::class,
        Commands\GetItemLevels::class,
        Commands\LoadData::class,
        Commands\GetProfessions::class,
        Commands\GetStatistics::class,
        Commands\GetTitles::class,
        Commands\GetReputation::class,
        Commands\GetRaids::class,
        Commands\GetQuests::class,
        Commands\SchedulerDaemon::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('get:data')->dailyAt('09:00');
        $schedule->command('get:titles')->dailyAt('12:00');
        $schedule->command('get:reputation')->dailyAt('15:00');
        $schedule->command('get:raids')->dailyAt('18:00');

        $schedule->command('get:characters')->hourlyAt(0);
        $schedule->command('get:ilvls')->hourlyAt(15);
        $schedule->command('get:professions')->hourlyAt(30);
        $schedule->command('get:statistics')->hourlyAt(45);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
