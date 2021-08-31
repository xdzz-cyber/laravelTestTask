@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <h1 class="d-inline-block">Employees</h1>
@stop

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @livewire("edit-employee-component",["employee_id"=>$employee_id])
                </div>
            </div>
        </div>
    </section>
@stop
