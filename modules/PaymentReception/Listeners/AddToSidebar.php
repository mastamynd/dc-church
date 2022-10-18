<?php

namespace Modules\PaymentReception\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddToSidebar
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
			'create-payment-reception-main',
			'read-payment-reception-main',
		])) {
				return;
		}

		$event->menu->dropdown(trans_choice('payment-reception::general.title', 2), function($sub) use($user){
			if($user->can('create-payment-reception-main')){
				$sub->route('payment-reception.receive-tithe', trans_choice('payment-reception::general.receive_tithe', 2), [], 10, []);
			}
			if($user->can('read-payment-reception-main')){
				$sub->route('payment-reception.index', trans_choice('payment-reception::general.received_tithes', 2), [], 20, []);
			}
		}, 21, [
			'title' => trans('payment-reception::general.title'),
			'icon'  =>'fas fa-hand-holding-usd'
		]);

		$event->menu->whereTitle(trans('bills.payments'), function($sub) use($user){
			if($user->can('read-common-reports')){
				$sub->route('demarcations.payments-made', trans('demarcations::general.payments_made'), [], 10, []);
			}
		}, 21,[
			'title' => trans('demarcations::general.payments_made'),
			'icon'  =>'fa fa-money-bill'
		]);
	
	}
}
