<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventRemindersNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Illuminate\Support\now;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to attendees of an event that the event is happening soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $events =Event::with("attendees.user")
        ->whereBetween("start_time",[now(),now()->addDay()])->get();
        $eventCount = $events->count();
        $eventLabel = Str::plural("event",$eventCount);
        $this->info("We found {$eventCount} {$eventLabel}");
        $events->each(fn($event)=>$event->attendees->each(
            fn($attendee)=> $attendee->user->notify(new EventRemindersNotification($event))));
        $this->info("Sent {$eventCount} email notification to remind them for the event ");
    }
}
