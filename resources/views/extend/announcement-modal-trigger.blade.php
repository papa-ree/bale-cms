@php
    $availableAnnouncement = App\Models\Announcement::whereAsModal(true)->wherePublished(true)->paginate(1);
@endphp

<div>
    @script
        <script>
            if (@js($availableAnnouncement->count() > 0)) {
                document.addEventListener('livewire:initialized', () => {
                    @this.dispatch('openModal', {
                        component: 'shared-components.modal.announcement-modal'
                    })
                });
            }
        </script>
    @endscript
</div>
