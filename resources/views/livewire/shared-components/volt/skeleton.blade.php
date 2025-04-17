<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{state, mount};

new class extends Component {};
?>

<div>
    <div
        class="flex flex-col h-auto bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
        <div class="flex flex-col items-center justify-center flex-auto p-4 md:p-5">
            <div class="flex justify-center">
                <div class="animate-spin inline-block size-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500"
                    role="status" aria-label="loading">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
