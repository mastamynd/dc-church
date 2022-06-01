<?php

namespace Modules\Demarcations\Listeners;

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
		$event->menu->dropdown(trans_choice('demarcations::general.name', 1), function($sub){
			$sub->route('demarcations.region.index', trans_choice('demarcations::general.region', 2), [], []);
			$sub->route('demarcations.subregion.index', trans_choice('demarcations::general.subregion', 2), [], []);
		}, 20, ['icon'=>'fas fa-map']);
	}
}
