<?php

namespace Paparee\BaleCms\App\Livewire\SharedComponents;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class TopbarNotificationDropdown extends Component
{
    public $hasNewNotifications = false;

    #[On('push-notification')]
    public function render()
    {
        // $this->hasNewNotifications = $this->notices()->count() > 0 ? true : false;
        $this->hasNewNotifications = $this->notices() ? true : false;
        return view('livewire.shared-components.topbar-notification-dropdown');
    }

    #[Computed]
    public function notices()
    {
        $notices = auth()->user()->unreadNotifications ?? null;
        // dd($notices);
        return $notices;
    }
}
