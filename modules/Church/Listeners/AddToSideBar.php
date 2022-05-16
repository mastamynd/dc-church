<?php

namespace Modules\Church\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddToSideBar
{    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->menu->dropdown(trans_choice('church::general.title', 2), function($sub){
			$sub->route('church.index', trans_choice('church::general.title', 2), [], []);
		}, 10, ['icon'=>'fas fa-church'])->order(10);

        $event->menu->dropdown(trans_choice('church::general.pastor', 2), function($sub){
			$sub->route('church.pastors.index', trans_choice('church::general.pastor', 2), [], []);
			$sub->route('church.pastors.statements', trans_choice('church::general.pastor_statement', 2), [], []);
		}, 10, ['icon'=>'fas fa-user-tie'])->order(10);
    }
}
