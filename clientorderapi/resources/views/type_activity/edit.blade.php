@extends('templates.base')
@section('title', 'Editar Tipo de Actividad')
@section('header', 'Editar Tipo de Actividad')
@section('content')
@include('templates.messages')


<div class="row">
    <div class="col-lg-12 mb-4">
        <form action="{{ route('type_activity.update', $typeActivity['id']) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row form-group">
                <div class="col-lg-12 mb-4">
                    <label for="description">Descripción</label>
                    <input type="text" class="form-control" name="description" id="description" required
                    value="{{ $typeActivity['description'] }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-primary btn-block">Guardar</button>
                </div>
                <br><br>
                <div class="col-lg-6">
                    <a href="#" class="btn btn-secondary btn-block">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
    
@endsection