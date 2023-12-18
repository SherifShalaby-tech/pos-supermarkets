@extends('layouts.app')
@section('title', __('lang.customer'))

@section('content')
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center">
            <h3 class="print-title">@lang('lang.all_customers')</h3>
        </div>
        <a style="color: white" href="{{ action('CustomerController@create') }}" class="btn btn-info"><i
                class="dripicons-plus"></i>
            @lang('lang.customer')</a>

    </div>
    <div class="row">
        <div class="col-sm-12">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('start_date', __('lang.start_date'), []) !!}
                                {!! Form::date('startdate', request()->start_date, ['class' => 'form-control','id'=>'startdate']) !!}
                            </div>
                        </div>
                
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('end_date', __('lang.end_date'), []) !!}
                                {!! Form::date('enddate', request()->end_date, ['class' => 'form-control ','id'=>'enddate']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('customer_type_id', __('lang.customer_type') . ':', []) !!}
                                {!! Form::select('customer_type_id', $customer_types, request()->customer_type_id, [
                                        'class' => 'form-control 
                                                            selectpicker',
                                        'data-live-search' => 'true',
                                        'placeholder' => __('lang.all'),
                                        'id'=>'customer_type_id'
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success mt-4 filter_product">@lang('lang.filter')</button>

                            <button class="btn btn-danger mt-4 clear_filters">@lang('lang.clear_filters')</button>
                        
                        </div> 
                    </div>
                </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="store_table" class="table" style="width: auto">
            <thead>
                <tr>
                    <th>@lang('lang.customer_type')</th>
                    <th>@lang('lang.photo')</th>
                    <th>@lang('lang.name')</th>
                    <th>@lang('lang.mobile_number')</th>
                    <th>@lang('lang.address')</th>
                    <th class="sum">@lang('lang.balance')</th>
                    <th class="sum_purchase">@lang('lang.purchases')</th>
                    <th class="sum_discounts">@lang('lang.discount')</th>
                    <th class="sum_points">@lang('lang.points')</th>
                    {{-- <th>@lang('lang.added_balance')</th> --}}
                    <th>@lang('lang.created_by')</th>
                    <th>@lang('lang.joining_date')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
            <tfoot>
                <tr>
                    {{-- <td></td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            store_table = $('#store_table').DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                // stateSave: true,
                buttons: buttons,
                processing: true,
                // searching: true,
                serverSide: true,
                aaSorting: [[2, 'asc']],
                // bPaginate: false,
                // bFilter: false,
                // bInfo: false,
                bSortable: true,
                bRetrieve: true,
                "ajax": {
                    "url": "/customer",
                    "data": function(d) {
                        d.startdate = $('#startdate').val();
                        d.enddate = $('#enddate').val();
                        d.customer_type_id = $('#customer_type_id').val()
                    }
                },
                columnDefs: [{
                    // "targets": [0,2, 3],
                    "orderable": true,
                    "searchable": true
                },
                {
                    "targets": [6], // Column 6 (purchases)
                    "orderable": true, // Enable sorting for the "purchases" column
                    "searchable": true
                }],
                columns: [
                    {
                        data: 'customer_type',
                        name: 'customer_type'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'customer_name',
                        name: 'customers.name'
                    },
                    {
                        data: 'mobile_number',
                        name: 'customers.mobile_number'
                    },
                    {
                        data: 'address',
                        name: 'customers.address'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'purchases',
                        name: 'purchases',
                        render: function (data, type, row) {
                            if (type === 'display' && row.id !== null) {
                                var url = '{{ url("customer") }}/' + row.id + '?show=purchases';
                                return '<a href="' + url + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        render: function (data, type, row) {
                            if (type === 'display' && row.id !== null) {
                                var url = '{{ url("customer") }}/' + row.id + '?show=discounts';
                                return '<a href="' + url + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'points',
                        name: 'points',
                        render: function (data, type, row) {
                            if (type === 'display' && row.id !== null) {
                                var url = '{{ url("customer") }}/' + row.id + '?show=points';
                                return '<a href="' + url + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    // {
                    //     data: 'total_balance_adjustment',
                    //     name: 'total_balance_adjustment'
                    // },
                    {
                        data: 'created_by',
                        name: 'users.name'
                    },
                    {
                        data: 'joining_date',
                        name: 'joining_date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ],
                createdRow: function(row, data, dataIndex) {

                },
                fnDrawCallback: function(oSettings) {
                    var intVal = function(i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                            i :
                            0;
                    };

                    this.api()
                        .columns(".sum,.sum_purchase,.sum_discounts,.sum_points", {
                            page: "current"
                        })
                        .every(function() {
                            var column = this;
                            if (column.data().count()) {
                                var sum = column.data().reduce(function(a, b) {
                                    a = intVal(a);
                                    if (isNaN(a)) {
                                        a = 0;
                                    }

                                    b = intVal(b);
                                    if (isNaN(b)) {
                                        b = 0;
                                    }

                                    return a + b;
                                });
                                $(column.footer()).html(
                                    __currency_trans_from_en(sum, false)
                                );
                            }
                        });
                },
            });

        });


        $(document).on('click', '.delete_customer', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.all_customer_transactions_will_be_deleted')",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    swal({
                        title: 'Please Enter Your Password',
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your password",
                                type: "password",
                                autocomplete: "off",
                                autofocus: true,
                            },
                        },
                        inputAttributes: {
                            autocapitalize: 'off',
                            autoComplete: 'off',
                        },
                        focusConfirm: true
                    }).then((result) => {
                        if (result) {
                            $.ajax({
                                url: check_password,
                                method: 'POST',
                                data: {
                                    value: result
                                },
                                dataType: 'json',
                                success: (data) => {

                                    if (data.success == true) {
                                        swal(
                                            'Success',
                                            'Correct Password!',
                                            'success'
                                        );

                                        $.ajax({
                                            method: 'DELETE',
                                            url: href,
                                            dataType: 'json',
                                            data: data,
                                            success: function(result) {
                                                if (result.success ==
                                                    true) {
                                                    swal(
                                                        'Success',
                                                        result.msg,
                                                        'success'
                                                    );
                                                    setTimeout(() => {
                                                        location
                                                            .reload();
                                                    }, 1500);
                                                    location.reload();
                                                } else {
                                                    swal(
                                                        'Error',
                                                        result.msg,
                                                        'error'
                                                    );
                                                }
                                            },
                                        });

                                    } else {
                                        swal(
                                            'Failed!',
                                            'Wrong Password!',
                                            'error'
                                        )

                                    }
                                }
                            });
                        }
                    });
                }
            });

            
        });
        $(document).on('click', '.filter_product', function() {
            store_table.ajax.reload();
        })
        $(document).on('click', '.clear_filters', function(e) {
            // e.preventDefault();
            $('#startdate').val('');
            $('#enddate').val('');
            $('#customer_type_id').val('')
            store_table.ajax.reload();
        });
    </script>
@endsection
