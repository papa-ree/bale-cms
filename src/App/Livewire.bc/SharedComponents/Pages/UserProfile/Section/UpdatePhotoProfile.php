<?php

namespace App\Livewire\SharedComponents\Pages\UserProfile\Section;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdatePhotoProfile extends Component
{
    use WithFileUploads;

    public $photo;
    public $state = [];

    public function mount()
    {
        $user = Auth::user();

        $this->state = array_merge([
            'email' => $user->email,
        ], $user->withoutRelations()->toArray());
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

    public function render()
    {
        return view('livewire.shared-components.pages.user-profile.section.update-photo-profile');
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater, LivewireAlert $alert)
    {
        $this->resetErrorBag();
        $validator = Validator::make($this->only(['photo']), [
            'photo' => 'required|image|mimes:png,jpg,jpeg|max:300',
        ]);

        if ($validator->fails()) {
            $this->dispatch('show-update-button', params: false);
            $this->dispatch('show-upload-button', params: true);
        }

        $validator->validated();

        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            $alert->title('Success!')->toast()->position('top-end')->success();
            
            return $this->redirectRoute('user-profile.index', navigate:true);
        }

    }
}
