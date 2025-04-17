<?php

namespace Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Section;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformation extends Component
{
    #[Locked]
    public $user;

    public $name;
    public $email;
    public $username;

    public function render()
    {
        return view('bale-cms::livewire.shared-components.pages.user-profile.section.update-profile-information');
    }

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->user = $this->getUserProperty();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->username = $this->user->username;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'email' => 'required|email:rfc,filter',
            'username' => ['required', 'alpha_dash:ascii', Rule::unique('users')->ignore($this->user)],
        ];

        return $rules;
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function updateProfileInformation()
    {
        $this->resetErrorBag();
        $this->validate();

        $this->getUserProperty()->update([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
        ]);

        // $this->flash('success', 'Profile updated successfully', [
        //     'toast' => true,
        //     'position' => 'top-end',
        // ]);
        session()->flash('saved', [
            'title' => 'Changes Saved!',
            'text' => 'You can safely close the tab!',
        ]);

        return $this->redirectRoute('user-profile.index', navigate:true);

    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    protected function getUserProperty()
    {
        return Auth::user();
    }
}
