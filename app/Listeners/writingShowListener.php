<?php

namespace leck\Listeners;

use leck\Events\writingShowEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

use leck\Writing_traffic;

class writingShowListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  writingShowEvent  $event
     * @return void
     */
    public function handle(writingShowEvent $event)
    {
        if (Auth::check()) {
          $visitor_id = Auth::user()->id;
        }else {
          $visitor_id = null;
        }
        Writing_traffic::grab($event->writing, $visitor_id);
    }
}
