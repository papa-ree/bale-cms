@props([
    'modalId' => 'default-modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'staticBackdrop' => false,
    'showCloseButton' => false,
    'zIndex' => 'z-[70]',
])

@php
    $sizeClasses =
        [
            'full' => 'max-w-full',
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'md:max-w-lg',
            'xl' => 'md:max-w-xl',
            '2xl' => 'lg:max-w-2xl',
            '3xl' => 'lg:max-w-3xl',
            '4xl' => 'xl:max-w-4xl',
            '5xl' => 'xl:max-w-5xl',
            '6xl' => '2xl:max-w-6xl',
            '7xl' => '2xl:max-w-7xl',
        ][$size] ?? 'sm:max-w-md';
@endphp

<div x-data="{
    open: false,
    showModalSpinner: false,

    useSpinner() {
        this.showModalSpinner = true
    },

    init() {
        // Listen for open modal event
        $wire.$on('openBaleModal', (params) => {
            if (params.id === '{{ $modalId }}') {
                this.open = true;
                document.body.classList.add('overflow-hidden');
            }
            this.showModalSpinner = false;
        });

        // Listen for close modal event
        $wire.$on('closeBaleModal', (params) => {
            if (params.id === '{{ $modalId }}') {
                this.close();
            }
        });

        $wire.$on('message-failed', () => {
            this.showModalSpinner = false;
        });
    },
    close() {
        this.open = false;
        this.showModalSpinner = false;

        document.body.classList.remove('overflow-hidden');
    }
}" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @keydown.escape.window="!{{ $staticBackdrop ? 'true' : 'false' }} && close()"
    class="fixed inset-0 {{ $zIndex }} overflow-y-auto" role="dialog" aria-modal="true" x-cloak>
    <!-- Backdrop -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="{{ $staticBackdrop ? '' : 'close()' }}"
        class="fixed inset-0 backdrop-blur-sm bg-gray-700/50 dark:bg-gray-900/70" aria-hidden="true"></div>

    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen p-4 sm:p-0">
        <!-- Modal content -->
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full {{ $sizeClasses }} bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all">
            <!-- Modal header -->
            @if (isset($header))
                <div class="flex items-start justify-between p-4 rounded-t dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $title }}
                    </h3>

                    @if ($showCloseButton)
                        <button type="button" @click="close()"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <x-lucide-x class="w-5 h-5" />
                            <span class="sr-only">Close modal</span>
                        </button>
                    @endif
                </div>
            @endif

            <!-- Modal body -->
            <div class="px-8 py-10 overflow-y-auto">
                {{ $slot }}
            </div>

            <!-- Modal footer -->
            @if (isset($actions))
                <div
                    class="flex items-center justify-start p-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                    {{ $actions }}
                </div>
            @endif

            <div class="absolute top-0 rounded-lg start-0 size-full bg-white/50 dark:bg-neutral-800/40"
                x-show="showModalSpinner">
            </div>

            <div class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 start-1/2"
                x-show="showModalSpinner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-loader-circle-icon size-10 lucide-loader-circle animate-spin text-emerald-400">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                </svg>
            </div>
        </div>

    </div>
</div>
