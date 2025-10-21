@extends('adminlte::page')


@section('content_header')
    <h1>Panel de Configuración de Proservi</h1>
@stop

@section('content')
    {{-- formulario --}}
    <div class="card-body">
        <form action="{{url('admin/configuraciones/create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Primera columna --}}
                <div class="col-md-4">
                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="">Nombre del Sitio Web</label><b> (*)</b>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                            </div>
                            <input type="text" class="form-control" value="{{ old('site_name',  $configuracion->site_name ?? '') }}" name="site_name" required>
                        </div>
                        @error('site_name')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="">Email</label><b> (*)</b>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" value="{{ old('site_email', $configuracion->site_email ?? '') }}" name="site_email" required>
                        </div>
                        @error('email')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>

                    
                </div>

                {{-- Segunda columna --}}
                <div class="col-md-4">
                    
                    {{-- Teléfono --}}
                    <div class="form-group">
                        <label for="">Teléfono</label><b> (*)</b>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" value="{{ old('site_phone', $configuracion->site_phone ?? '') }}" name="site_phone" required>
                        </div>
                        @error('site_phone')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group">
                        <label for="">Descripción</label><b> (*)</b>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                            </div>
                            <input type="text" class="form-control" value="{{ old('site_description', $configuracion->site_description ?? '') }}" name="site_description" required>
                        </div>
                        @error('site_description')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                </div>

                {{-- Tercera columna - logo --}}
                <div class="col-md-4">
                    <div class="form-group ">
                        <label for="site_logo">Logo</label><b> (*)</b>
                        <input type="file" id="file" name="site_logo" accept=".jpg, .jpeg, .png" class="form-control">
                        @error('site_logo')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                        <br>
                        <output id="list">
                            @if (isset($configuracion->site_logo))
                                <img class="thumb thumbnail" src="{{url($configuracion->site_logo)}}" width="70%" title="site_logo">
                            @endif
                        </output>
                        <script>
                            function archivo(evt){
                                var files = evt.target.files;
                                for(var i = 0, f; f = files[i]; i++){
                                    if (!f.type.match('image.*')) {
                                        continue;
                                    }
                                    var reader = new FileReader();
                                    reader.onload = (function(theFile){
                                        return function(e){
                                            document.getElementById("list").innerHTML = '<img class="thumb thumbnail" src="' + e.target.result + '" width="70%">';
                                        };
                                    })(f);
                                    reader.readAsDataURL(f);
                                }
                            }
                            document.getElementById('file').addEventListener('change', archivo, false);
                        </script>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{url('/home')}}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
    {{-- Fin del formulario --}}
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop