<?php

use Livewire\Component;

new class extends Component
{
    public string $message = '';
};
?>

<div class="border-l-4 border-red-600 bg-red-100 text-red-600 font-bold p-3 my-2 text-sm">
    {{$message}}
</div>