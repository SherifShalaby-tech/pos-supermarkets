<style type="text/css">
    .text-center {
        text-align: center;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    /*Css related to printing of barcode*/
    .label-border-outer {
        border: 0.1px solid grey !important;
    }

    .label-border-internal {
        /*border: 0.1px dotted grey !important;*/
    }

    .sticker-border {
        border: 0.1px dotted grey !important;
        overflow: hidden;
        box-sizing: border-box;
    }

    #preview_box {
        padding-left: 30px !important;
    }

    @media print {
        .content-wrapper {
            border-left: none !important;
            /*fix border issue on invoice*/
        }

        .label-border-outer {
            border: none !important;
        }

        .label-border-internal {
            border: none !important;
        }

        .sticker-border {
            border: none !important;
        }

        #preview_box {
            padding-left: 0px !important;
        }

        #toast-container {
            display: none !important;
        }

        .tooltip {
            display: none !important;
        }

        .btn {
            display: none !important;
        }
    }

    @media print {
        #preview_body {
            display: block !important;
        }
    }

    @page {
        margin-top: 0in;
        margin-bottom: 0in;
        margin-left: 0in;
        margin-right: 0in;

    }
</style>

<button class="btn btn-success" onclick="window.print()">@lang('lang.print')</button>
<div id="preview_body">
    <div style="height:3in !important;width:8in !important; line-height:12px;  display: inline-block;"
        class="sticker-border text-center">

        <div class="row">
            <div class="col-md-4" style="font-size: 20px; padding-left:10px">
                @if (isset($site_title))
                    <p style="text-align: left; word-wrap: break-word;">
                        @lang('lang.store', [], $invoice_lang) : {{ $site_title }}
                    </p>
                @endif
                @if (!empty($employee))
                    <p style="text-align: left; word-wrap: break-word;">
                        @lang('lang.employee_name', [], $invoice_lang): {{ $employee->user->name }}
                    </p>
                @endif
                <br><br><br>

            </div>

            <div class="col-md-4" style="font-size: 14px; margin-top: -30px;"></div>
            <div class="col-md-12" style="font-size: 14px; margin-top: -30px; text-align: center;">
                @if (!empty($employee))
                    <div style="border: none !important;height:86px !important;width:8in !important; line-height:12px;  display: inline-block;"
                        class="sticker-border text-center">
                        <p style="display: inline-block; max-width: 600px; overflow: hidden;">
                            <span style="display: inline-block; max-width: 100%; height:100px;">
                                {!! DNS1D::getBarcodeSVG($password, 'C128', 4, 80, '#2A3239') !!}
                            </span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
