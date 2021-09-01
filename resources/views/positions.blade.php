@extends('adminlte::page')

@section('title', 'Positions')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1 class="d-inline-block">Positions</h1>
        </div>
        <div class="col-md-6">
            <a class="fa-pull-right d-inline-block text-white bg-gradient-gray btn btn-info mx-3" href="{{route('positions.add')}}">Add position</a>
            <form id="logout-form" action="{{ url('logout') }}" method="POST">
                {{ csrf_field() }}
                <button class="fa-pull-right d-inline-block text-white bg-gradient-gray btn btn-info" type="submit">Logout</button>
            </form>
        </div>
    </div>

@stop

@section('plugins.Sweetalert2', true)


@section("content")
    <section class="py-5 border">
        <div class="container">
            <div class="row">
                <table class="table table-bordered yajra-datatable">
                    <thead>
                    <tr>
                        <th>name</th>
                        <th>last update</th>
                        <th>management</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop



@push('js')
    <script>
        $(document).ready(function() {


            let table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getPositions') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'updated_at', name: 'updated_at'},
                    {
                        data: 'management',
                        name: 'management',
                        orderable: true,
                        searchable: true
                    },
                ]
            });


            $(document).on('click','#removeSweetAlertButton',function (e){
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        window.location = $(this).attr('href');
                    } else{
                        return false;
                    }
                });
            })

        })
    </script>
@endpush
