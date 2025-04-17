@php
    $model = $attributes->wire('model')->value();
@endphp
<div>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.20.0"></script>


    <div wire:ignore>
        <x-label value="tag (optional)" />
        <input name='input-custom-dropdown'
            class="block w-full px-3 py-2 text-sm border-gray-200 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
            placeholder='write some tags'>
    </div>

    <script>
        var input = document.querySelector('input[name="input-custom-dropdown"]'),
            tagify = new Tagify(input, {
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(`','`),
                whitelist: @json($availableTag),
                maxTags: 5,
                dropdown: {
                    maxItems: 20, // <- mixumum allowed rendered suggestions
                    classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0, // <- show suggestions on focus
                    closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                }
            })
        input.addEventListener('change', onChange)

        function onChange(e) {
            @this.set('{{ $model }}', e.target.value);
        }
    </script>

</div>
