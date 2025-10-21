@extends('adminlte::page')

@section('content_header')
    <h1><b>Subcategoría/Modificar datos de la subcategoría</b></h1>
    <hr>
@stop
 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Actualice los datos del formulario</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subcategorias.update', $subcategoria->id) }}" method="post" id="subcategoriaForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria_id">Categoría</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                        </div>
                                        <select name="categoria_id" id="categoria_id" class="form-control" required>
                                            <option value="">Seleccione una categoría</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" 
                                                    {{ (old('categoria_id', $subcategoria->categoria_id) == $categoria->id) ? 'selected' : '' }}>
                                                    {{ $categoria->nombre_cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('categoria_id')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Subcategoría</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nombre" 
                                               value="{{ old('nombre', $subcategoria->nombre) }}" 
                                               placeholder="Ingrese nombre de subcategoría..." required>
                                    </div>
                                    @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                        </div>
                                        <textarea class="form-control" name="descripcion" rows="3"
                                                  placeholder="Ingrese descripción...">{{ old('descripcion', $subcategoria->descripcion) }}</textarea>
                                    </div>
                                    @error('descripcion')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                    <a href="{{ route('admin.subcategorias.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script>
        // Validación básica del formulario
        document.getElementById('subcategoriaForm').addEventListener('submit', function(e) {
            let categoria = document.getElementById('categoria_id');
            let nombre = document.getElementsByName('nombre')[0];
            
            if (categoria.value === '') {
                e.preventDefault();
                alert('Por favor, seleccione una categoría');
                categoria.focus();
                return false;
            }
            
            if (nombre.value.trim() === '') {
                e.preventDefault();
                alert('Por favor, ingrese un nombre para la subcategoría');
                nombre.focus();
                return false;
            }
        });
    </script>
@stop