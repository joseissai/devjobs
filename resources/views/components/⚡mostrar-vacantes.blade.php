<?php

use Livewire\Component;
use App\Models\Vacante;

new class extends Component
{    
    protected $listeners = ['elimina_vacante'];

    public function with(): array
    {
        return [
            'vacantes' => Vacante::where('user_id', auth()->user()->id)->paginate(10)
        ];
        
    }

    public function elimina_vacante($vacanteId)
    {
        $vacante = Vacante::find($vacanteId);
        if($vacante)
        {
            $vacante->delete();
        }        
    }

};
?>

<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        @forelse ($vacantes as $vacante)                            
            <div class="p-6 text-gray-900 border-b border-gray-200 dark:text-gray-100 md:flex md:justify-between md-items-center">
                <div class="space-y-3">
                    <a href="{{route('vacantes.show', $vacante->id)}}" class="text-xl font-bold">{{$vacante->titulo}}</a>
                    <p class="text-sm text-gray-600 font-bold">{{$vacante->empresa}}</p>
                    <p class="text-sm text-gray-500">Último día: {{ $vacante->ultimo_dia->format('d/m/yy') }}</p>
                </div>
                <div class="flex flex-col md:flex-col items-stretch gap-3 mt-5 md:mt-0 uppercase">
                    <a href="{{route('candidatos.index', $vacante)}}" class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold text-center">{{$vacante->candidatos->count()}} Candidatos</a>
                    <a href="{{ route('vacantes.edit', $vacante->id) }}" class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold text-center">Editar</a>
                    <button 
                        wire:click="$dispatch('mostrar_alerta', {vacanteId: {{$vacante->id}} } )"
                        class="bg-red-800 py-2 px-4 rounded-lg text-white text-xs font-bold text-center"
                        >Eliminar</button>
                </div>
            </div>
        @empty
            <p class="p-3 text-center text-sm text-gray-600">No hay vacantes que mostrar</p>
        @endforelse
    </div>

    <div class="flex justify-center mt-10">
        {{$vacantes->links()}}        
    </div>    

</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('mostrar_alerta', data => {            
            const vacanteId = data.vacanteId ?? data[0]?.vacanteId ?? data;
            Swal.fire({
                title: "Eliminar vacante?",
                text: "Una vacante eliminada, no se puede recuperar!",
                icon: "Atención!!",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "Cancelar"
                }).then((result) => {
                if (result.isConfirmed)
                {
                    // Disparar un evento a la parte lógica
                    Livewire.dispatch('elimina_vacante', {vacanteId:vacanteId});

                    Swal.fire({
                        title: "Eliminada!",
                        text: "Su vacante ha sido eliminada.",
                        icon: "success"});
                }
                 
                });
        })
    </script>
@endpush