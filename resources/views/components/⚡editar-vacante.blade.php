<?php

use App\Models\Salario;
use App\Models\Categoria;
use App\Models\Vacante;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    public $vacante_id;

    public $salarios;
    public $categorias;

    public $titulo;
    public $salario;
    public string $categoria = '';
    public $empresa;
    public $ultimo_dia;
    public $descripcion;
    public $imagen;
    public $imagen_nueva;

    use WithFileUploads;



    // Reglas de validación
    protected $rules =[
        'titulo' => 'required|string',
        'salario' => 'required|numeric|gt:0',
        'categoria' => 'required|numeric|gt:0',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen_nueva' => 'nullable|image|max:1024'
    ];


    // Consultar BD
    public function mount(Vacante $vacante): void
    {        

        $this->salarios = Salario::all();
        $this->categorias = Categoria::all();     

        $this->vacante_id = $vacante->id;
        
        $this->titulo = $vacante->titulo;
        $this->salario = $vacante->salario_id;
        $this->categoria = $vacante->categoria_id;

        $this->empresa = $vacante->empresa;
        $this->ultimo_dia = $vacante->ultimo_dia->format('Y-m-d');
        $this->descripcion = $vacante->descripcion;
        $this->imagen = $vacante->imagen;

    }    

    public function editarVacante()
    {
        $datos = $this->validate();

        //Si hay una nueva imagen
        if($this->imagen_nueva)
        {
            $this->imagen_nueva = $this->imagen_nueva->store('vacantes', 'public');
            $datos['imagen'] = str_replace('vacantes/', '', $this->imagen_nueva);  
        }

        //Encontrar la vacante a editar
        $vacante = Vacante::find($this->vacante_id);

        //Asignar los valores
        $vacante->titulo = $datos['titulo'];
        $vacante->salario_id = $datos['salario'];
        $vacante->categoria_id = $datos['categoria'];
        $vacante->empresa = $datos['empresa'];
        $vacante->ultimo_dia = $datos['ultimo_dia'];
        $vacante->descripcion = $datos['descripcion'];
        $vacante->imagen = $datos['imagen'] ?? $vacante->imagen;

        // Guardar la vacante
        $vacante->save();

        //Redireccionar
        session()->flash('mensaje', 'La vacante se actualizó correctamente.');
        return redirect()->route('vacantes.index');
    }

    
};
?>

<form action="" class="md:w-1/2 space-y-5" wire:submit.prevent='editarVacante'>

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
                    <option value="{{$salario->id}}">{{$salario->salario}}</option>
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
                    <option value="{{$categoria->id}}">{{$categoria->categoria}}</option>
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

        <!-- Imagen -->
        <div class="mt-4">

            <div class="my-5 w-80">
                <x-input-label :value="__('Imagen actual')" />
                <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'vacantes' . DIRECTORY_SEPARATOR . $imagen) }}" alt="  {{ 'Imagen Vacante: ' . $titulo }}">
            </div> 

            <div class="my-5 w-80">
                @if($imagen_nueva)
                    <x-input-label :value="__('Imagen nueva')" />
                    <img src="{{ $imagen_nueva->temporaryUrl() }}" alt="">
                @endif 
            </div>

            <x-input-label for="imagen_nueva" :value="__('Imagen nueva')" />
            <x-text-input 
                id="imagen" 
                class="block mt-1 w-full" 
                type="file" 
                Wire:model="imagen_nueva"
                accept="image/*"
                />           

            <x-input-error :messages="$errors->get('imagen_nueva')" class="mt-2" />
        </div>

            <x-primary-button class="ms-4">
                {{ __('Guardar cambios') }}
            </x-primary-button>

</form>