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
                    <th>@lang('lang.purchases')</th>
                    <th>@lang('lang.discount')</th>
                    <th>@lang('lang.points')</th>
                    <th>@lang('lang.created_by')</th>
                    <th>@lang('lang.joining_date')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
            <tfoot>
                <tr>
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
                aaSorting: [
                    [2, 'asc']
                ],
                "ajax": {
                    "url": "/customer",
                },
                columnDefs: [{
                    // "targets": [0,2, 3],
                    "orderable": true,
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
                        name: 'purchases'
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'points',
                        name: 'points'
                    },
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
                        .columns(".sum", {
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
    </script>
@endsection
