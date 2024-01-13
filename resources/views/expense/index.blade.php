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
                                {{-- <form action=""> --}}
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('expense_category_id', __('lang.expense_category'), []) !!}
                                                    {!! Form::select('expense_category_id', $expense_categories, request()->expense_category_id, ['class' => 'form-control', 'placeholder' => __('lang.all'), 'data-live-search' => 'true','id'=>'expense_category_id']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('expense_beneficiary_id', __('lang.expense_beneficiary'), []) !!}
                                                    {!! Form::select('expense_beneficiary_id', $expense_beneficiaries, request()->expense_beneficiary_id, ['class' => 'form-control', 'placeholder' => __('lang.all'), 'data-live-search' => 'true','id'=>'expense_beneficiary_id']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('store_id', __('lang.store'), []) !!}
                                                    {!! Form::select('store_id', $stores, request()->store_id, ['class' => 'form-control', 'placeholder' => __('lang.all'), 'data-live-search' => 'true','id'=>'store_id']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('store_paid_id', __('lang.store') . ' ' . __('lang.paid_by'), []) !!}
                                                    {!! Form::select('store_paid_id', $stores, request()->store_paid_id, ['class' => 'form-control', 'placeholder' => __('lang.all'), 'data-live-search' => 'true','id'=>'store_paid_id']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('start_date', __('lang.start_date'), []) !!}
                                                    {!! Form::text('start_date', request()->start_date, ['class' => 'form-control','id'=>'start_date']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('start_time', __('lang.start_time'), []) !!}
                                                    {!! Form::text('start_time', request()->start_time, ['class' => 'form-control time_picker sale_filter','id'=>'start_time']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('end_date', __('lang.end_date'), []) !!}
                                                    {!! Form::text('end_date', request()->end_date, ['class' => 'form-control','id'=>'end_date']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {!! Form::label('end_time', __('lang.end_time'), []) !!}
                                                    {!! Form::text('end_time', request()->end_time, ['class' => 'form-control time_picker sale_filter','id'=>'end_time']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-success mt-4 filter_product">@lang('lang.filter')</button>
                    
                                                <button class="btn btn-danger mt-4 clear_filters">@lang('lang.clear_filters')</button>
                                            
                                            </div> 
                                        </div>
                                    </div>
                                {{-- </form> --}}
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <div class="table-responsive">
                                <table class="table" style="width: auto" id="expense_table">
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
                                            <td class="sum"></td>
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
            store_table = $('#expense_table').DataTable({
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
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [[2, 'asc']],
                bSortable: true,
                bRetrieve: true,
                "ajax": {
                    "url": "/expense",
                    "data": function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.expense_category_id = $('#expense_category_id').val()
                        d.expense_beneficiary_id = $('#expense_beneficiary_id').val()
                        d.store_id = $('#store_id').val()
                        d.store_paid_id = $('#store_paid_id').val()
                        d.start_time = $('#start_time').val()
                        d.end_time = $('#end_time').val()
                    }
                },
                columnDefs: [{
                    // "targets": [0,2, 3],
                    "orderable": true,
                    "searchable": true
                },
                {
                    "targets": [2],
                    "orderable": true,
                    "searchable": true
                }],
                columns: [
                    {
                        data: 'expense_category',
                        name: 'expense_category'
                    },
                    {
                        data: 'beneficiary',
                        name: 'beneficiary'
                    },
                    {
                        data: 'store',
                        name: 'store'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'creation_date',
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
                        data: 'paid_by',
                        name: 'paid_by'
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
        $(document).on('click', '.filter_product', function() {
            store_table.ajax.reload();
        })
        $(document).on('click', '.clear_filters', function(e) {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#expense_category_id').val('')
            $('#expense_beneficiary_id').val('')
            $('#store_id').val('')
            $('#store_paid_id').val('')
            $('#start_time').val('')
            $('#end_time').val('')
            store_table.ajax.reload();
        });
    </script>
@endsection
