@props(['title', 'subtitle'])
<div class="w-full mx-auto mb-4 overflow-hidden border border-gray-200 sm:mb-6 dark:border-gray-700 rounded-xl">
    <div class="relative bg-white dark:bg-gray-800">
        <div class="flex-row items-center justify-between p-4 space-y-3 sm:p-6 sm:flex sm:space-y-0 sm:space-x-4">
            <div>
                <p class="text-sm md:text-[17px] tracking-wide font-medium sm:font-semibold dark:text-white">
                    {{ __($title) }}
                </p>
                <p class="hidden mt-2 text-sm text-gray-500 dark:text-gray-400 sm:block">
                    {{ __($subtitle) }}
                </p>
            </div>
            {{ $action }}
        </div>
    </div>
</div>
