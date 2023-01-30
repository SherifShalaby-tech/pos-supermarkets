@extends('layouts.app')
@section('title', __('lang.printers'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>@lang('lang.add_new_printer')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('printers.store')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('name', __('lang.name') . ' *', []) !!}
                                            <div class="input-group my-group">
                                                {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('lang.name')]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="products">{{trans('lang.products')}}</label>
                                            <div class="input-group my-group">
                                                <select id="products" data-live-search="true" class="selectpicker form-control" name="products[]" multiple>
                                                    @foreach($products as $product)
                                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_active">{{trans('lang.status')}}</label>
                                            <div class="input-group my-group">
                                                <select id="is_active" class="selectpicker form-control" name="is_active">
                                                    <option value="1">{{trans('lang.active')}}</option>
                                                    <option value="0">{{trans('lang.not_active')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="i-checks">
                                            <input id="is_cashier" name="is_cashier" type="checkbox" value="1" class="form-control-custom">
                                            <label for="is_cashier">
                                                <strong>
                                                    @lang('lang.is_cashier')
                                                </strong>
                                            </label>
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
        </div>
    </section>


@stop
