<?php

namespace App\Livewire\SharedComponents\Pages\UserProfile;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User Profile')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.shared-components.pages.user-profile.index');
    }
}
