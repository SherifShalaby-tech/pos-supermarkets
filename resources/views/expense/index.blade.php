@extends('layouts.app')
@section('title', __('lang.expenses'))


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="print-title">@lang('lang.expenses')</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div action="">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('expense_category_id', __('lang.expense_category'), []) !!}
                                                    {!! Form::select('expense_category_id', $expense_categories, request()->expense_category_id, ['class' => 'form-control filters', 'placeholder' => __('lang.all'), 'data-live-search' => 'true']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('expense_beneficiary_id', __('lang.expense_beneficiary'), []) !!}
                                                    {!! Form::select('expense_beneficiary_id', $expense_beneficiaries, request()->expense_beneficiary_id, ['class' => 'form-control filters', 'placeholder' => __('lang.all'), 'data-live-search' => 'true']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('store_id', __('lang.store'), []) !!}
                                                    {!! Form::select('store_id', $stores, request()->store_id, ['class' => 'form-control filters', 'placeholder' => __('lang.all'), 'data-live-search' => 'true']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('store_paid_id', __('lang.store') . ' ' . __('lang.paid_by'), []) !!}
                                                    {!! Form::select('store_paid_id', $stores, request()->store_paid_id, ['class' => 'form-control filters', 'placeholder' => __('lang.all'), 'data-live-search' => 'true']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('start_date', __('lang.start_date'), []) !!}
                                                    {!! Form::text('start_date', request()->start_date, ['class' => 'form-control filters']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('start_time', __('lang.start_time'), []) !!}
                                                    {!! Form::text('start_time', request()->start_time, ['class' => 'form-control time_picker sale_filter filters']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('end_date', __('lang.end_date'), []) !!}
                                                    {!! Form::text('end_date', request()->end_date, ['class' => 'form-control filters']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('end_time', __('lang.end_time'), []) !!}
                                                    {!! Form::text('end_time', request()->end_time, ['class' => 'form-control time_picker sale_filter filters']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <br>
                                                <button type="submit"
                                                        class="btn btn-success mt-2 get_filters">@lang('lang.filter')</button>
                                                <button
                                                    class="btn btn-danger mt-2 ml-2 clear_filter">@lang('lang.clear_filter')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <table class="table" id="expense_table">
                                    <thead>
                                    <tr>
                                        <th>@lang('lang.expense_category')</th>
                                        <th>@lang('lang.beneficiary')</th>
                                        <th>@lang('lang.store')</th>
                                        <th class="sum">@lang('lang.amount_paid')</th>
                                        <th>@lang('lang.created_by')</th>
                                        <th>@lang('lang.creation_date')</th>
                                        <th>@lang('lang.payment_date')</th>
                                        <th>@lang('lang.next_payment_date')</th>
                                        <th>@lang('lang.store') @lang('lang.paid_by')</th>
                                        <th>@lang('lang.source_of_payment')</th>
                                        <th>@lang('lang.files')</th>
                                        <th class="notexport">@lang('lang.action')</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><strong>@lang('lang.total')</strong></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            expense_table = $('#expense_table').DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                order: [[2, 'desc']],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                stateSave: true,
                buttons: buttons,
                processing: true,
                serverSide: true,
                // aaSorting: [
                //     [2, 'desc']
                // ],
                "ajax": {
                    "url": "/expense",
                    "data": function(d) {

                        d.expense_id = $('#expense_id').val();
                        d.expense_category_id = $('#expense_category_id').val();
                        d.expense_beneficiary_id = $('#expense_beneficiary_id').val();
                        d.store_id = $('#store_id').val();
                        d.store_paid_id = $('#store_paid_id').val();
                        d.start_date = $('#start_date').val();
                        d.start_time = $("#start_time").val();
                        d.end_date = $('#end_date').val();
                        d.end_time = $("#end_time").val();

                    }
                },
                columnDefs: [{
                    "targets": [0, 3, 9, 8],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                    data: 'expense_category_name',
                    name: 'expense_category_name',
                    searchable: false
                },
                    {
                        data: 'expense_beneficiary_name',
                        name: 'expense_beneficiary_name',
                        searchable: false
                    },

                    {
                        data: 'store',
                        name: 'store.name'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by_user.name',
                        searchable: false
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date'
                    },
                    {
                        data: 'next_payment_date',
                        name: 'next_payment_date'
                    },
                    {
                        data: 'store',
                        name: 'store'
                    },
                    {
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'files',
                        name: 'files'
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
                            var total = 0;
                            column.data().each(function(group, i) {
                                total += intVal(group);
                            });
                            var footer_html = total;

                            $(column.footer()).html(
                                footer_html
                            );
                        });
                },
            });
            $(document).on('click', '.get_filters', function() {

                expense_table.ajax.reload();
            })
            $(document).on('click', '.clear_filter', function() {
                $('.filters').val('');
                $('.filters').selectpicker('refresh')
                expense_table.ajax.reload();
            })
        });
    </script>
@endsection
