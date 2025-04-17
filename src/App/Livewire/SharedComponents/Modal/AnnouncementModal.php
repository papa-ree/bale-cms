<?php

namespace App\Livewire\SharedComponents\Modal;

use App\Models\Announcement;
use App\Models\SiteConfig;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class AnnouncementModal extends ModalComponent
{
    use WithPagination;
    use WithoutUrlPagination;

    public $has_announcement;

    public function render()
    {
        return view('livewire.shared-components.modal.announcement-modal');
    }

    #[Computed()]
    public function availableAnnouncement()
    {
        return Announcement::whereAsModal(true)->wherePublished(true)->paginate(1);
    }

    public static function modalMaxWidth(): string
    {
        return SiteConfig::find('announcement_modal_size')->value;
    }
}
