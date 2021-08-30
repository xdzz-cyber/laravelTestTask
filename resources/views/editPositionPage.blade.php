@extends('adminlte::page')

@section('title', 'Positions')

@section('content_header')
    <h1 class="d-inline-block">Positions</h1>
@stop

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @livewire("edit-position-component",["positionId"=>$positionId])
                </div>
            </div>
        </div>
    </section>
@stop
