@extends('layouts.app')
@section('title',trans('lang.customer_insurances'))
@section('content')
    <div class="container-fluid">
        <a style="color: white" href="{{route('customer-insurances.create')}}" class="btn btn-info"><i
                class="dripicons-plus"></i>
            @lang('lang.add_new_deposit')</a>
    </div>
{{--    <div class="card mt-3">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-3">--}}
{{--                    <div class="form-group">--}}
{{--                        <label>{{trans('lang.customers')}}</label>--}}
{{--                        <select class="form-control" name="customer_id">--}}
{{--                            @foreach($customers as $customer)--}}
{{--                                <option value="{{$customer->id}}">{{$customer->name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3">--}}
{{--                    <div class="form-group">--}}
{{--                        <label>{{trans('lang.products')}}</label>--}}
{{--                        <select class="form-control" name="product_id">--}}
{{--                            @foreach($products as $product)--}}
{{--                                <option value="{{$product->id}}">{{$product->name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3">--}}
{{--                    <div class="form-group">--}}
{{--                        <label>{{trans('lang.status')}}</label>--}}
{{--                        <select class="form-control" name="status">--}}
{{--                            <option value="Available">Available</option>--}}
{{--                            <option value="Pending">Pending</option>--}}
{{--                            <option value="Late">Late</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3">--}}
{{--                    <div class="form-group">--}}
{{--                        <label>{{trans('lang.return_date')}}</label>--}}
{{--                        <input type="date" name="return_date" class="form-control">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="col-sm-12">
        <br>
        <div class="table-responsive">
            <table class="table dataTable">
                <thead>
                <tr>
                    <th>@lang('lang.deposit_date')</th>
                    <th>@lang('lang.customer_name')</th>
                    <th>@lang('lang.customer_phone')</th>
                    <th>@lang('lang.amount_paid')</th>
                    <th>@lang('lang.product_description')</th>
                    <th>@lang('lang.return_date')</th>
                    <th>@lang('lang.cashier_name')</th>
                    <th>@lang('lang.penalties')</th>
                    <th>@lang('lang.penalties_cause')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($deposits as $deposit)
                    <tr>
                        <td>{{$deposit->created_at ?? '-'}}</td>
                        <td>{{$deposit->customer->name ?? '-'}}</td>
                        <td>{{$deposit->customer->mobile_number ?? ''}}</td>
                        <td>{{$deposit->deposit_amount}}</td>
                        <td>{{$deposit->product->product_details ?? '-'}}</td>
                        <td>{{$deposit->return_date}}</td>
                        <td>{{$deposit->admin->name}}</td>
                        <td>{{$deposit->penalties ?? '-'}}</td>
                        <td>{{$deposit->cause_the_penalties ?? '-'}}</td>
                        <td>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#EditModal{{$deposit->id}}">
                                <i
                                    class="fa fa-pencil-square-o"></i>
                            </a>
                            <a data-href="{{route('customer-insurances.destroy',$deposit->id)}}"
                               data-check_password="{{action('UserController@checkPassword', Auth::user()->id)}}"
                               class="btn btn-danger text-white delete_item"><i
                                    class="fa fa-trash"></i></a>
                            <!-- Button trigger modal -->
                            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal{{$deposit->id}}">
                                Received
                            </a>
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
                                    <form action="{{route('customer-insurances.received')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$deposit->id}}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{trans('lang.penalties')}}</label>
                                                    <input value="{{$deposit->penalties ?? ''}}" required="required" type="text" name="penalties" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{trans('lang.the_amount_of_the_fine')}}</label>
                                                    <input value="{{$deposit->penalty_amount ?? ''}}" required="required" type="number" name="penalty_amount" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label>{{trans('lang.penalties_cause')}}</label>
                                                <textarea class="form-control" name="cause_the_penalties">{{$deposit->cause_the_penalties ?? ''}}</textarea>
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
                    <div class="modal fade" id="EditModal{{$deposit->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('customer-insurances.update','test')}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{$deposit->id}}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{trans('lang.product')}}</label>
                                                    <select required="required" class="form-control" name="item_id">
                                                        @foreach($products as $product)
                                                            <option {{$product->id == $deposit->product_id ? 'selected' : ''}} value="{{$product->id}}">{{$product->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{trans('lang.customer')}}</label>
                                                    <select required="required" class="form-control" name="customer_id">
                                                        @foreach($customers as $customer)
                                                            <option {{$customer->id == $deposit->customer_id ? 'selected' : ''}} value="{{$customer->id}}">{{$customer->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{trans('lang.status')}}</label>
                                                    <select required="required" class="form-control" name="status">
                                                        <option {{$deposit->status == 'Available' ? 'selected' : ''}} value="Available">Available</option>
                                                        <option {{$deposit->status == 'Pending' ? 'selected' : ''}} value="Pending">Pending</option>
                                                        <option {{$deposit->status == 'Late' ? 'selected' : ''}} value="Late">Late</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{trans('lang.insurance_amount')}}</label>
                                                    <input value="{{$deposit->deposit_amount}}" required="required" type="number" name="insurance_amount" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{trans('lang.return_date')}}</label>
                                                    <input value="{{$deposit->return_date}}" required="required" type="date" name="return_date" class="form-control">
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
                </tbody>
            </table>
        </div>
    </div>


@stop
