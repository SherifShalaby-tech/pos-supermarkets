@extends('layouts.app')
@section('title',trans('lang.add_new_product'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>@lang('lang.add_new_product')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('item-borrowed.store')}}" method="POST">
                                @csrf
                                <input type="hidden" name="admin_id" value="{{auth()->user()->id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{trans('lang.product_name')}}</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>{{trans('lang.customer')}}</label>--}}
{{--                                            <select required="required" class="form-control" name="customer_id">--}}
{{--                                                @foreach($clients as $customer)--}}
{{--                                                    <option value="{{$customer->id}}">{{$customer->name}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{trans('lang.insurance_amount')}}</label>
                                            <input required="required" type="number" name="deposit_amount" class="form-control">
                                        </div>
                                    </div>
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>{{trans('lang.return_date')}}</label>--}}
{{--                                            <input required="required" type="date" name="return_date" class="form-control">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
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
        </div>
    </section>

@stop
