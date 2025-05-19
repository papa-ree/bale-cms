<?php

namespace App\Livewire\SharedComponents\Pages\UserProfile\Section;

use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Livewire\Component;

class UpdatePassword extends Component
{
    
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];
    
    public function render()
    {
        return view('livewire.shared-components.pages.user-profile.section.update-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserPasswords  $updater
     * @return void
     */
    public function updatePassword(UpdatesUserPasswords $updater)
    {
        $this->resetErrorBag();

        $updater->update(Auth::user(), $this->state);

        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_'.Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
            ]);
        }

        $this->state = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->flash('success', 'Password updated successfully', [
            'toast' => true,
            'position' => 'top-end',
        ]);

        return $this->redirectRoute('user-profile.index', navigate:true);
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

}
