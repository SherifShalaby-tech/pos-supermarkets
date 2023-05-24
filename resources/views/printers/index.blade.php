@extends('layouts.app')
@section('title', __('lang.printers'))
@section('content')
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center">
            <h3 class="print-title">@lang('lang.printers')</h3>
        </div>
        <a style="color: white" href="{{route('printers.create')}}" class="btn btn-info"><i
                class="dripicons-plus"></i>
            @lang('lang.add_new_printer')</a>
    </div>
    <div class="col-sm-12">
        <br>
        <div class="table-responsive">
            <table class="table dataTable">
                <thead>
                <tr>
                    <th>@lang('lang.name')</th>
                    <th>@lang('lang.status')</th>
                    <th>@lang('lang.createdBy')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($printers as $printer)
                    <tr>
                        <td>
                            {{$printer->name}}
                        </td>
                        <td>
                            {{$printer->Active()}}
                        </td>
                        <td>
                            {{auth()->user()->name}}
                        </td>
                        <td>
                            <a data-href="{{route('printers.edit', $printer->id)}}"
                               data-container=".view_modal"
                               class="btn btn-primary btn-modal text-white edit_job"><i
                                    class="fa fa-pencil-square-o"></i></a>
                            <a data-href="{{route('printers.destroy', $printer->id)}}"
                               data-check_password="{{action('UserController@checkPassword', Auth::user()->id)}}"
                               class="btn btn-danger text-white delete_item"><i
                                    class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop
@section('javascript')
<script>

</script>


@stop
