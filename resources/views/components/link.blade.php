@php    
    $classes = "text-sm text-gray-600 dark:text-gray-400 hover:text-lime-400 dark:hover:text-lime-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors";
@endphp
<div>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</div>