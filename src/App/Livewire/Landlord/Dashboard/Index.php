<?php

namespace Paparee\BaleCms\App\Livewire\Landlord\Dashboard;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Index extends Component
{
    public function render()
    {
        return view('bale-cms::livewire.landlord.dashboard.index');
    }
}
