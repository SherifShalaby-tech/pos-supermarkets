@extends('layouts.app')
@section('title', __('lang.product_'.$status ?? "all"))

@section('content')

    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ __('lang.product_'.$status ?? "all") }}</h4>
                        </div>
                        <div class="card-header d-flex align-items-center">
                            <h3>
                                <a href="{{ route("addConvolution",["id"=> $id ]) }}">@lang("lang.removed_expiry")</a>
                            </h3>
                        </div>

                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>@lang('lang.image')</th>
                                <th>@lang('lang.name')</th>
                                <th>@lang('lang.product_code')</th>
                                <th>@lang('lang.quantity_of_expired_stock_removed')</th>
                                <th>@lang('lang.value_of_the_removed_stocks')</th>
                                <th>@lang('lang.date_of_the_removal')</th>
                                <th>@lang('lang.date_of_purchase_of_the_expired_stock_removed')</th>
                                <th>@lang('lang.deleted_by')</th>
                                <th>@lang('lang.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($product_expires)
                                @foreach ($product_expires as $product_expiry)
                                    <tr>
                                        <td>
                                            @if(!empty($product_expiry->product->getFirstMediaUrl('product')))
                                              <img src="{{ $product_expiry->product->getFirstMediaUrl('product') }}" height="50px" width="50px">
                                            @else
                                             <img src="{{ asset('/uploads/' . session('logo')) }}" height="50px" width="50px">
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product_expiry->variation->name != "Default")
                                                {{$product_expiry->variation->name}}
                                            @else
                                                {{$product_expiry->product->name}}
                                            @endif
                                        </td>
                                        <td>{{$product_expiry->variation->sub_sku}}</td>

                                        <td>{{ $product_expiry->quantity_of_expired_stock_removed }}</td>
                                        <td>{{ $product_expiry->value_of_removed_stocks }}</td>
                                        <td>{{ $product_expiry->created_at }}</td>
                                        <td> {{ $product_expiry->date_of_purchase_of_expired_stock_removed }} </td>
                                        <td>{{$product_expiry->addedBy->name}}</td>
                                        <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">@lang('lang.action')
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                                         @can('adjustment.customer_balance_adjustment.delete')
                                                        <li>
                                                            <a data-href="{{action('ProductController@deleteExpiryRow', $product_expiry->id)}}"
                                                               data-check_password="{{action('UserController@checkPassword', Auth::user()->id)}}"
                                                               class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                                                @lang('lang.delete')</a>
                                                        </li>
                                                         @endcan
                                                    </ul>
                                                </div>
                                        </td>
                                    </tr>

                                @endforeach
                            @endisset
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">

    </script>
@endsection
