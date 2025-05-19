<?php

namespace App\Livewire\Landlord\Dashboard;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.landlord.dashboard.index');
    }
}
