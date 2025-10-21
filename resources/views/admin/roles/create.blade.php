@extends('adminlte::page')

@section('content_header')
    <h1><b>Roles/Registro de un nuevo rol</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{url('admin/roles/create')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nombre del rol</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Escriba aquí..." required>
                                    </div>
                                    @error('name')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                    <a href="{{url('/admin/roles')}}" class="btn btn-secondary">Cancelar</a>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop

@section('footer')
    <!--
    <div class="float-right d-none d-sm-block">
        <b>Versión</b> 1.0.0
    </div>
    -->
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">Gonzalo Felipez</a>.</strong> Todos los derechos reservados.
@stop

@section('css')

@stop

@section('js')

@stop
