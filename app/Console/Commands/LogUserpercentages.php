<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserLog;
use App\Models\UserPercentageLog;
use Illuminate\Console\Command;

class LogUserpercentages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:userpercentages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Percentage of Users Logged in per day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateTime = now()->startOfDay()->toDateTimeString();

        // Calculate Percentage of Users Logged in today
        $logs = UserLog::where('created_at', '>=', $dateTime)->count();
        $users = User::all()->count();
        $returned_users = ($logs / $users) * 100;

        // Log Percentages
        $log = UserPercentageLog::where('created_at', '>=', $dateTime)->first();
        if (! $log) {
            $newLog = new UserPercentageLog;
            $newLog->percentage = $returned_users;
            $newLog->number = $logs;
            $newLog->total = $users;
            $newLog->save();
        } else {
            $new = ($logs / $log->total) * 100;
            $log->percentage = $new;
            $log->number = $logs;
            $log->save();
            $returned_users = $new;
        }
    }
}
