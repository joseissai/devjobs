<?php

use Livewire\Component;

new class extends Component
{
    //public $vacante;
};
?>

<div class="p-10 text-white">
    <div class="mb-5">
        <h3 class="font-bold text-3xl text-gray-300 my-3">{{$vacante->titulo}}</h3>
    </div>
    <div class="md:grid md:grid-cols-2 bg-gray-50 p-4 my-10 sm:rounded-lg">
        <p class="font-bold text-sm uppercase text-gray-500">Empresa:
            <span class="normal-case font-normal">{{$vacante->empresa}}</span>
        </p>
        <p class="font-bold text-sm uppercase text-gray-500">Último día:
            <span class="normal-case font-normal">{{$vacante->ultimo_dia->toFormattedDateString()}}</span>
        </p>
        <p class="font-bold text-sm uppercase text-gray-500">Categoría:
            <span class="normal-case font-normal">{{$vacante->categoria->categoria}}</span>
        </p>
        <p class="font-bold text-sm uppercase text-gray-500">Salario:
            <span class="normal-case font-normal">{{$vacante->salario->salario}}</span>
        </p>
    </div>

    <div class="md:grid md:grid-cols-6 gap-4 items-start ">
        <div class="md:col-span-2">
            <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'vacantes' . DIRECTORY_SEPARATOR . $vacante->imagen) }}" alt="  {{ 'Imagen Vacante: ' . $vacante->titulo }}">
        </div>
        <div class="md:col-span-4">
            <p class="text-2xl font-bold mb-3 text-gray-700 dark:text-gray-300">
                Descripción del puesto:
            </p>
            <p class="text-base font-normal text-gray-600 dark:text-gray-400 whitespace-pre-line leading-relaxed">
                {{ $vacante->descripcion }}
            </p>        
        </div>            
    </div>

    @guest
    <div class="mt-5 bg-gray-50 border border-dashed p-5 text-center text-gray-500">
        <p>¿Deseas aplicar o postularte a esta vacante?
            <a class="font-bold text-indigo text-indigo-600" href="{{route('register')}}">Obten una cuenta y aplica a esta y otras vacantes.</a>
        </p>
    </div>
    @endguest

    @auth
    @cannot('create', App\Models\Vacante::class)
        <livewire:postular-vacante :vacante="$vacante" />
    @endcannot 
    @endauth




</div>