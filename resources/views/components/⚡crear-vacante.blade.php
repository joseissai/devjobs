<?php

use App\Models\Salario;
use App\Models\Categoria;
use App\Models\Vacante;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{

    public $salarios;
    public $categorias;

    public string $titulo = '';
    public string $salario = '';
    public string $categoria = '';
    public string $empresa = '';
    public $ultimo_dia;
    public string $descripcion = '';
    public $imagen;

    use WithFileUploads;



    // Reglas de validación
    protected $rules =[
        'titulo' => 'required|string',
        'salario' => 'required|numeric|gt:0',
        'categoria' => 'required|numeric|gt:0',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required|image|max:1024'
    ];


    // Consultar BD
    public function mount(): void
    {
        $this->salarios = Salario::all();
        $this->categorias = Categoria::all();        
    }    

    public function crearVacante(){
        $datos = $this->validate();

        // Almacenar la imagen
        $imagen = $this->imagen->store('vacantes', 'public');
        $datos['imagen'] = str_replace('vacantes/', '', $imagen);        

        // Crear la vacante
        Vacante::create([
            'titulo' => $datos['titulo'], 
            'salario_id' => $datos['salario'], 
            'categoria_id' => $datos['categoria'], 
            'empresa' => $datos['empresa'], 
            'ultimo_dia' => $datos['ultimo_dia'], 
            'descripcion' => $datos['descripcion'], 
            'imagen' => $datos['imagen'], 
            'user_id' => auth()->user()->id, 
        ]);

        //Crear un mensaje
        session()->flash('mensaje', 'La vacante se publicó correctamente.');

        // Redireccionar al usuario hacia la ventana previa
        return redirect()->route('vacantes.index');
        

    }

    
};
?>

<form action="" class="md:w-1/2 space-y-5" wire:submit.prevent='crearVacante'>

        <!-- Titulo -->
        <div class="mt-4">
            <x-input-label for="titulo" :value="__('Titulo Vacante')" />
            <x-text-input 
                id="titulo" 
                class="block mt-1 w-full" 
                type="text" 
                Wire:model.live="titulo" 
                :value="old('titulo')" 
                autocomplete="username" 
                placeholder="Titulo Vacante"
                />
            <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
        </div>         

        <!-- Salario Mensual -->
        <div class="mt-4">
            <x-input-label for="salario" :value="__('Salario Mensual')" />
            <select 
                for="salario" 
                Wire:model.live="salario" 
                id="salario"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
                >
                <option value="">-- Seleccione --</option>
                @foreach ($salarios as $salario)
                    <option value="{{$salario->id}} ">{{$salario->salario}}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('salario')" class="mt-2" />         
        </div>

        <!-- Categoria Mensual -->
        <div class="mt-4">
            <x-input-label for="categoria" :value="__('Categoría')" />
            <select 
                for="categoria" 
                Wire:model.live="categoria" 
                id="categoria"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
                >
                <option value="">-- Seleccione --</option>
                @foreach ($categorias as $categoria)
                    <option value="{{$categoria->id}} ">{{$categoria->categoria}}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('categoria')" class="mt-2" />                  
        </div>

        <!-- Empresa -->
        <div class="mt-4">
            <x-input-label for="empresa" :value="__('Empresa')" />
            <x-text-input 
                id="empresa" 
                class="block mt-1 w-full" 
                type="text" 
                Wire:model.live="empresa" 
                :value="old('empresa')" 
                autocomplete="empresa" 
                placeholder="Empresa: ej: Netflix, Uber, Shopify"
                />
            <x-input-error :messages="$errors->get('empresa')" class="mt-2" />
        </div>

        <!-- Último día -->
        <div class="mt-4">
            <x-input-label for="ultimo_dia" :value="__('Último día para postularse')" />
            <x-text-input 
                id="ultimo_dia" 
                class="block mt-1 w-full" 
                type="date" 
                Wire:model.live="ultimo_dia" 
                :value="old('ultimo_dia')" 
                autocomplete="ultimo_dia" 
                />
            <x-input-error :messages="$errors->get('ultimo_dia')" class="mt-2" />
        </div>      
        
        <!-- Descripción del puesto -->
        <div class="mt-4">
            <x-input-label for="descripcion" :value="__('Descripción del puesto')" />
            <textarea 
                Wire:model.live="descripcion" 
                placeholder="Descripción general del puesto, experiencia"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-72"
            ></textarea>
            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
        </div>

        <!-- Imágen -->
        <div class="mt-4">
            <x-input-label for="imagen" :value="__('Imágen')" />
            <x-text-input 
                id="imagen" 
                class="block mt-1 w-full" 
                type="file" 
                Wire:model="imagen"
                accept="image/*"
                />
            <div class="my-5 w-80">
                @if($imagen)
                    Imagen:
                    <img src="{{ $imagen->temporaryUrl() }}" alt="">
                @endif 
            </div>
            <x-input-error :messages="$errors->get('imagen')" class="mt-2" />
        </div>

            <x-primary-button class="ms-4">
                {{ __('Crear Vacante') }}
            </x-primary-button>

</form>