<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Candidatos;
use App\Models\Vacante;
use App\Notifications\NuevoCandidato;


new class extends Component
{
    public $cv;
    public $vacante;

    protected $rules = [
        'cv' => 'required|mimes:pdf'
    ];

    use WithFileUploads;

    public function mount(Vacante $vacante)
    {
        $this->vacante = $vacante;
    }

    public function postularme()
    {
        $datos = $this->validate();

        // Almacenar CV en el HDD
        $cv = $this->cv->store('cv', 'public');
        $datos['cv'] = str_replace('cv/', '', $cv);    

        // Crear la vacante
        $this->vacante->candidatos()->create([
            'user_id' => auth()->user()->id, 
            'cv' => $datos['cv'],
        ]);

        // Crear notificación y enviar email
        $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo, auth()->user()->id));

        // Mostrar al usuario un mensaje de ok
        session()->flash('mensaje', 'Se envió correctamente tu información, mucha suerte!');
        return redirect()->back();


    }

    
};
?>

<div class="bg-gray-100 p-5 mt-10 flex-col justify-center items-center sm:rounded-lg">    
    @if(session()->has('mensaje'))
        <h3 class="text-center text-2xl font-bold my-4 text-gray-500">Postulación exitosa!!</h3>
        <x-mensaje title="¡Postulación exitosa!" :text="session('mensaje')"/>
    @else
    <h3 class="text-center text-2xl font-bold my-4 text-gray-500">Postularme a esta vacante</h3>
    <form wire:submit.prevent="postularme" class="w-96 mt-5">
            <div class="mb-4">
                <x-input-label for="cv" :value="__('Curriculum u Hoja de vida (PDF)')" />
                <x-text-input 
                    id="cv" 
                    type="file" 
                    accept=".pdf" 
                    class="block mt-1 w-full" 
                    wire:model="cv"                
                />
                <x-input-error :messages="$errors->get('cv')" class="mt-2" />
            </div>
            <x-primary-button class="my-5">
                {{ __('Postularme') }}
            </x-primary-button>
            
        </form>    
    @endif
    
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush