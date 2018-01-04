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
        Commands\GetAchievements::class,
        Commands\CheckAuctionHouse::class,
        Commands\SchedulerDaemon::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        // Daily commands
        $schedule->command('get:data')->dailyAt('09:00');
        $schedule->command('get:titles')->dailyAt('10:00');
        $schedule->command('get:professions --recipes=true')->dailyAt('11:00');
        $schedule->command('get:raids')->dailyAt('12:00');
        $schedule->command('get:quests')->dailyAt('13:00');
        $schedule->command('get:achievements')->dailyAt('14:00');
        $schedule->command('get:reputation')->dailyAt('15:00');

        // Hourly commands
        $schedule->command('get:characters')->hourlyAt(10);
        $schedule->command('get:ilvls')->hourlyAt(30);
        $schedule->command('get:statistics')->hourlyAt(50);

        // Load auction data more often
        $schedule->command('get:auctions')->everyTenMinutes();
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
