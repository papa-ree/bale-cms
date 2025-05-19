<div
    class="flex overflow-hidden text-base bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700">
    <div class="relative inline-flex py-4 group">
        <div class="absolute opacity-30 dark:opacity-40 -inset-px bg-emerald-200 rounded-xl blur-md">
        </div>
        <div class="bg-emerald-300 drop-shadow-xl w-[3px] h-full rounded-r-lg">
        </div>
    </div>

    <div class="p-4 grow md:p-5 gap-x-4">
        <h2 class="mb-4 text-xl font-medium">{{ $title }}</h2>
        {{ $slot }}
    </div>
</div>
