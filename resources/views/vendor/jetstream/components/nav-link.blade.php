@props(['active'])

@php
$classes = ($active ?? false)
? 'px-4 py-2 mt-2 text-sm text-center md:text-left font-semibold text-gray-900 bg-gray-200 rounded-lg
dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white
dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900
hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline'
: 'px-4 py-2 mt-2 text-sm text-center md:text-left font-semibold bg-transparent rounded-lg dark-mode:bg-transparent
dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white
dark-mode:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200
focus:outline-none focus:shadow-outline';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
