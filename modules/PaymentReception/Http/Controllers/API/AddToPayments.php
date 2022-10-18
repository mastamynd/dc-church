<?php

namespace Modules\PaymentReception\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddToPaymants
{    
	/**
	* Handle the event.
	*
	* @param  object  $event
	* @return void
	*/
	public function handle(Event $event)
	{
		$user = user();

		if (!$user->can([
			'read-common-reports',
		])) {
				return;
		}

		$event->menu->whereTitle(trans('bills.payments'), function($sub) use($user){
			if($user->can('read-common-reports')){
				$sub->route('demarcations.payments_made', trans('demarcations::general.payments_made'), [], 10, []);
			}
		}, 21,[
			'title' => trans('demarcations::general.payments_made'),
			'icon'  =>'fa fa-money-bill'
		]);
	
	}
}