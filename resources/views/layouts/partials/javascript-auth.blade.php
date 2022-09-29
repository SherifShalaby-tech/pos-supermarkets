@php
$moment_time_format = App\Models\System::getProperty('time_format') == '12' ? 'hh:mm A' : 'HH:mm';
@endphp
<script>
    var moment_time_format = "{{$moment_time_format}}";
</script>
<script type="text/javascript" src="{{asset('js/lang/'.session('language').'.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/jquery/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/popper.js/umd/popper.min.js') }}">
</script>
<script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/daterange/js/moment.min.js') }}"></script>

<script type="text/javascript" src="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.'.session('language').'.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>

<script type="text/javascript" src="{{asset('vendor/bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>
<script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap-select.min.js') }}"></script>
