<?php

namespace Modules\Church\Listeners;

use App\Events\Module\SettingShowing as Event;

class ShowInSettingsPage
{
	/**
	 * Handle the event.
	 *
	 * @param  Event $event
	 * @return void
	 */
	public function handle(Event $event)
	{
		$event->modules->settings['church'] = [
			'name' => trans('church::general.settings_name'),
			'description' => trans('church::general.settings_description'),
			'url' => route('church.settings.edit'),
			'icon' => 'fa fa-church',
		];
	}
}