<h5 class="col-12 pb-4">{!! trans('lang.app_setting_flutterwave_credentials') !!}</h5>
<div class="d-flex flex-column col-sm-12 col-md-6">
    <!-- Route Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('flutterwave_key', trans("lang.app_setting_flutterwave_key"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('flutterwave_key', setting('flutterwave_key'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_flutterwave_key_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.app_setting_flutterwave_key_help") }}
            </div>
        </div>
    </div>
    <!-- Route Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('flutterwave_secret', trans("lang.app_setting_flutterwave_secret"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('flutterwave_secret', setting('flutterwave_secret'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_flutterwave_secret_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.app_setting_flutterwave_secret_help") }}
            </div>
        </div>
    </div>
</div>
<div class="d-flex flex-column col-sm-12 col-md-6">
    <!-- Boolean Enabled Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('enable_flutterwave', trans("lang.app_setting_enable_flutterwave"),['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        {!! Form::hidden('enable_flutterwave', 0, ['id'=>"hidden_enable_flutterwave"]) !!}
        <div class="col-9 icheck-{{setting('theme_color')}}">
            {!! Form::checkbox('enable_flutterwave', 1, setting('enable_flutterwave')) !!}
            <label for="enable_flutterwave"></label>
        </div>
    </div>
</div>
