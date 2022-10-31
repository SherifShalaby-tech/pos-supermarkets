<!-- Modal -->
<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="edit">@lang('lang.edit')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open(['url' => route('printers.update','test'), 'method' => 'POST']) !!}
        @method('put')
        @csrf
        <input type="hidden" name="id" value="{{$printer->id}}">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">@lang('lang.name')</label>
                        <input type="text" class="form-control" value="{{$printer->name}}" name="name" id="name" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="is_active">{{trans('lang.status')}}</label>
                        <div class="input-group my-group">
                            <select id="is_active" class="form-control" name="is_active">
                                <option {{$printer->is_active == true ? 'selected' : ''}} value="1">{{trans('lang.active')}}</option>
                                <option {{$printer->is_active == false ? 'selected' : ''}} value="0">{{trans('lang.not_active')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
