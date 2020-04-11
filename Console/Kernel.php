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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:updateOrderNormal')
        ->everyFifteenMinutes();

        $schedule->call('App\Http\Controllers\TransactionsController@UpdateRates')
         ->everyFifteenMinutes();
         

        // //  $schedule->call('App\Http\Controllers\TransactionsController@Cron')
        // //  ->everyMinute();

        // //  $schedule->call('App\Http\Controllers\TransactionsController@DepositBitcoin')
        // //  ->everyMinute();

        // //  $schedule->call('App\Http\Controllers\TransactionsController@DepositLitecoin')
        // //  ->everyMinute();

        //  $schedule->call('App\Http\Controllers\TransactionsController@DepositMonero')
        //  ->everyMinute();
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
