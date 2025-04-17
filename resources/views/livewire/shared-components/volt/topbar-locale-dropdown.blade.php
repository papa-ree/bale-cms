<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <div class="hs-dropdown hs-tooltip relative inline-flex mr-2 [--placement:bottom-right] [--auto-close:inside]"
        x-data="{ locale: '{{ app()->getLocale() }}' }">

        <button type="button" id="hs-locale-dropdown" x-cloak @click="showIndicator=false"
            class="hs-tooltip-toggle inline-flex justify-center bg-gray-50 text-gray-600 font-serif items-center gap-2 h-[2.375rem] w-[2.375rem] rounded-full align-middle focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-xs dark:bg-gray-800 dark:hover:bg-slate-800 dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800">
            @if (app()->getLocale() == 'id')
                🇮🇩
                {{-- {{ __('Bahasa Indonesia') }} --}}
                {{-- <img class="size-4" src="{{ asset('asset/default/indonesia-flag.svg') }}" alt="🇮🇩"> --}}
            @else
                🇬🇧
                {{-- {{ __('English') }} --}}
                {{-- <img class="size-4" src="{{ asset('asset/default/uk-flag.svg') }}" alt="🇬🇧"> --}}
            @endif
        </button>


        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[15rem] bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700 antialiased"
            aria-labelledby="hs-locale-dropdown">
            <div class="px-4 py-3 space-y-3">
                <div class="">
                    <a href="{{ app()->getLocale() == 'en' ? url('lang/id') : '#' }}"
                        class="flex items-center gap-2 text-gray-700 dark:text-gray-400 {{ app()->getLocale() == 'id' ? 'pointer-events-none opacity-50' : '' }}">
                        {{-- <img class="size-4" src="{{ asset('asset/default/indonesia-flag.svg') }}" alt="🇮🇩"> --}}
                        🇮🇩
                        <p>Bahasa Indonesia</p>
                    </a>
                </div>
                <div class="">
                    <a href="{{ app()->getLocale() == 'id' ? url('lang/en') : '#' }}"
                        class="flex items-center gap-2 text-gray-700 dark:text-gray-400 {{ app()->getLocale() == 'en' ? 'pointer-events-none opacity-50' : '' }}">
                        {{-- <img class="size-4" src="{{ asset('asset/default/uk-flag.svg') }}" alt="🇬🇧"> --}}
                        🇬🇧
                        <p>English</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
