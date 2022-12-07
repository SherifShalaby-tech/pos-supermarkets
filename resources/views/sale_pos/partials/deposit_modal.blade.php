<div class="modal fade" role="dialog" id="deposit_modal">
    <div class="modal-dialog" role="document" style="width: 75%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('lang.insurance_amount')</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i
                            class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{trans('lang.product')}}</label>
                            <input type="text" name="ItemBorrowed[name]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{trans('lang.customer')}}</label>
                            <select class="form-control" name="ItemBorrowed[customer_id]">
                                @foreach($clients as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{trans('lang.status')}}</label>
                            <select class="form-control" name="ItemBorrowed[status]">
                                <option value="Available">Available</option>
                                <option value="Pending">Pending</option>
                                <option value="Late">Late</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{trans('lang.insurance_amount')}}</label>
                            <input type="number" name="ItemBorrowed[insurance_amount]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{trans('lang.return_date')}}</label>
                            <input type="date" name="ItemBorrowed[return_date]" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-dismiss="modal" id="deposit_submit">@lang('lang.submit')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lang.close')</button>
                    </div>
                </div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
