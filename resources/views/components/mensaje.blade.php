@props([
    'title' => '¡Operación Exitosa!',
    'text' => '',
    'icon' => 'success'
])

<div 
    x-data 
    x-init="
        Swal.fire({
            title: '{{ $title }}',
            text: '{{ $text }}',
            icon: '{{ $icon }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        })
    "
></div>