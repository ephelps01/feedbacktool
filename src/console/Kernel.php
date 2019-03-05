<?php

namespace Ecd\Feedbacktool\Console;

use Ecd\Feedbacktool\Models\Issue;
use Ecd\Feedbacktool\Models\Gitnote;
use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * Define the package's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        parent::schedule($schedule);

        $schedule->call(function () {
        	Issue::checkForUpdates();
        })->twiceDaily(8, 13);
    }
}