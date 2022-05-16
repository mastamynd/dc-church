<?php

namespace Modules\GlPostingSettings\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddToAdminMenu
{
    /**
     * Handle the event.
     *
     * @param AdminCreated $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->menu->whereTitle('Banking', function($sub){
            $sub->route('gl-posting-settings.index', trans('gl-posting-settings::general.name'), []);
        }, 40);

        $event->menu->whereTitle('Sales', function($sub){
            $sub->route('gl-posting-settings.customers-caution', trans('customizations.caution_money'), []);
        }, 40);

        $event->menu->whereTitle('Banking', function($sub){
            $sub->route('gl-posting-settings.recon-index', trans('gl-posting-settings::general.reconsiliation'), []);
        }, 41);
    }
}