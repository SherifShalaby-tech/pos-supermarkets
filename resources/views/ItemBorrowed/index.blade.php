@extends('layouts.app')
@section('title',trans('lang.Borrowed_products'))
@section('content')
    <div class="container-fluid">
        <a style="color: white" href="{{route('item-borrowed.create')}}" class="btn btn-info"><i
                class="dripicons-plus"></i>
            @lang('lang.add_new_product')</a>
    </div>
    <div class="col-sm-12">
        <br>
        <div class="table-responsive">
            <table class="table dataTable">
                <thead>
                <tr>
                    <th>@lang('lang.product_name')</th>
                    <th>@lang('lang.creator_name')</th>
                    <th>@lang('lang.status')</th>
                    <th>@lang('lang.customer_name')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
                </thead>
                <tbody>
                @isset($products)
                    @foreach ($products as $deposit)
                        <tr>
                            <td>{{$deposit->name}}</td>
                            <td>{{$deposit->admin->name}}</td>
                            <td style="color:#FFF !important; background-color: @if($deposit->status == 'Available') green !important; @elseif($deposit->status == 'Pending') yellow  @else red  @endif">{{$deposit->status}}</td>
                            <td>{{$deposit->customer->name ?? '-'}}</td>
                            <td>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#EditProduct{{$deposit->id}}">
                                    <i
                                        class="fa fa-pencil-square-o"></i>
                                </a>
                                <a data-href="{{route('item-borrowed.destroy',$deposit->id)}}"
                                   data-check_password="{{action('UserController@checkPassword', Auth::user()->id)}}"
                                   class="btn btn-danger text-white delete_item"><i
                                        class="fa fa-trash"></i></a>
                                <!-- Button trigger modal -->
                                @if($deposit->status == 'Available')
                                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal{{$deposit->id}}">
                                        Give
                                    </a>
                                @endif
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{$deposit->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('item-borrowed.give')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$deposit->id}}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{trans('lang.insurance_amount')}}</label>
                                                        <input value="{{$deposit->deposit_amount}}" required="required" type="number" name="deposit_amount" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{trans('lang.return_date')}}</label>
                                                        <input required="required" type="date" name="return_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{trans('lang.customer')}}</label>
                                                        <select required="required" class="form-control" name="customer_id">
                                                            @foreach($clients as $customer)
                                                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="EditProduct{{$deposit->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('item-borrowed.update','test')}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{$deposit->id}}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{trans('lang.product_name')}}</label>
                                                        <input value="{{$deposit->name}}" type="text" name="name" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{trans('lang.insurance_amount')}}</label>
                                                        <input value="{{$deposit->deposit_amount}}" required="required" type="number" name="deposit_amount" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mt-5">
                                                    <div class="form-group">
                                                        <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn"
                                                               class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </div>


@stop
