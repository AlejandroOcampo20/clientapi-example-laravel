@extends('templates.base')
@section('title','Ordenes')
@section('header', 'Ordenes')
@section('content')

    <div class="row">
        <div class="col-lg-12 mb-4 d-grid gap-2 d-md-block">
            <a href="{{ route('order.create') }}" class="btn btn-primary">Crear</a>
        </div>
    </div>    
    
    @include('templates.messages')

    <div class="row">
        <div class="col-lg-12 mb-4">
            <table id="table_data" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th>Id Causal</th>
                        <th>Id Observación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <body>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order['id'] }}</td>
                        <td>{{ $order['legalization_date'] }}</td>
                        <td>{{ $order['address'] }}</td>
                        <td>{{ $order['city'] }}</td>
                        <td>{{ $order['causal']['description'] }}</td>
                        <td>@if($order['observation']){{ $order['observation']['description'] }}@endif</td>
                        <td>
                            <a href="{{ route('order.edit',$order['id']) }}" class="btn btn-primary btn-circle btn-sm" title="Editar"><i class="far fa-edit"></i>
                            </a>
                            <a href="{{ route('order.destroy',$order['id']) }}" class="btn btn-danger btn-circle btn-sm" class="Eliminar" onclick="return remove();"><i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </body>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/general.js') }}"></script>
@endsection