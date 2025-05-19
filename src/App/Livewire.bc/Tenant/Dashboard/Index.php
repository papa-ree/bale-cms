<?php

namespace Paparee\BaleCms\App\Livewire\Tenant\Dashboard;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.tenant.dashboard.index');
    }
}
