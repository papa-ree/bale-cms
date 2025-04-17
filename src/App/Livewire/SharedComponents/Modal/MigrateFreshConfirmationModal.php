<?php

namespace App\Livewire\SharedComponents\Modal;

use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class MigrateFreshConfirmationModal extends ModalComponent
{
    use LivewireAlert;

    public function render()
    {
        return view('livewire.shared-components.modal.migrate-fresh-confirmation-modal');
    }

    public function migrateFresh()
    {
        $this->authorize('migrate fresh');

        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        Artisan::call('optimize:clear');

        $this->alert('success', 'Migrated and Seeded', [
            'toast' => true,
            'position' => 'top-end'
        ]);

        $this->closeModal();

        return to_route('login');
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function closeModalOnEscape(): bool
    {
        return false;
    }
}
