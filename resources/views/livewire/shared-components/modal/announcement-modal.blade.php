<div>

    @if ($this->availableAnnouncement->count() > 0)
        <div
            class="p-2 mx-auto overflow-hidden text-left transition-all transform bg-white rounded-lg dark:bg-gray-800 dark:text-white sm:p-4">

            <div class="mb-3 text-center capitalize">Announcement</div>

            @foreach ($this->availableAnnouncement as $announcement)
                <a @if ($announcement->announcement_url_mode) href="http://{{ $announcement->announcement_url }}"
                    @else
                    wire:navigate.hover
                    href="{{ route('landing.view-page', $announcement->page_slug ?? '404') }}" @endif
                    class="space-y-3 focus:ring-0 focus:border-0 active:border-0 active:ring-0">
                    {!! html_entity_decode($announcement->announcement_content) !!}
                </a>
            @endforeach

            {{-- pagination --}}
            @if ($this->availableAnnouncement->count() > 0)
                <div class="pt-4">
                    {{ $this->availableAnnouncement->links('components.extend.modal-pagination') }}
                </div>
            @endif

        </div>
    @endif

</div>
