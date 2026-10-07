<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Candidatos Vacante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200  my-10">
                    <h1 class="text-2xl font-bold text-center mb-10">Candidatos Vacante: {{$vacante->titulo}}</h1>
                    <div class="md:flex md:justify-center p-5">

                        <ul = class="divide-y divide-gray-200 w-full">
                            @forelse ($vacante->candidatos as $candidato)
                                <li class="p-3 flex items-center">
                                    {{-- Lado izquierdo --}}
                                    <div class="flex-1">
                                        <p class="tetx-xl font-medium text-gray-100">{{$candidato->user->name}}</p>
                                        <p class="tetx-sm text-gray-300">{{$candidato->user->email}}</p>
                                        <p class="tetx-sm text-gray-300 font-medium">Día que se postuló: <span class="font-normal">{{$candidato->user->created_at->diffForHumans()}}</span></p>
                                    </div>
                                    {{-- lado derecho --}}
                                    <div>
                                        <a 
                                            href="{{asset('storage' . DIRECTORY_SEPARATOR . 'cv' . DIRECTORY_SEPARATOR . $candidato->cv)}}" 
                                            target="_blank"
                                            rel="noreferrer noopnener"
                                            class="inline-flex items-center shadow-sm px-2.5 py-0.5 border-gray-300 text-sm leading-5 font-medium text-gray-200 hover:bg-gray-500 rounded-lg"
                                            >Ver CV</a>
                                    </div>
                                </li>
                            @empty
                                <p class="p-3 text-center text-sm text-gray-600">No hay candidatos aún.</p>
                            @endforelse
                        </ul>

                    </div>                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
