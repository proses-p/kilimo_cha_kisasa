<?php

namespace App\Console\Commands;

use App\Models\Crop_activity;
use App\Notifications\ActivityReminderNotification;
use Illuminate\Console\Command;

class SendActivityReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-activity-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $activities = Crop_activity::whereDate(
            'scheduled_date',
            now()->toDateString()
        )->where('is_completed', false)->get();

        foreach ($activities as $activity) {
            $user = $activity->crop->farm->user;
            $user->notify(new ActivityReminderNotification($activity));
        }

        return Command::SUCCESS;
    }
}
