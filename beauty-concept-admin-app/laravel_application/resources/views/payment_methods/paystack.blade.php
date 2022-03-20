<h5 class="col-12 pb-4">{!! trans('lang.app_setting_paystack_credentials') !!}</h5>
<div class="d-flex flex-column col-sm-12 col-md-6">
    <!-- Route Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('paystack_key', trans("lang.app_setting_paystack_key"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('paystack_key', setting('paystack_key'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_paystack_key_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.app_setting_paystack_key_help") }}
            </div>
        </div>
    </div>
    <!-- Route Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('paystack_secret', trans("lang.app_setting_paystack_secret"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('paystack_secret', setting('paystack_secret'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_paystack_secret_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.app_setting_paystack_secret_help") }}
            </div>
        </div>
    </div>
</div>
<div class="d-flex flex-column col-sm-12 col-md-6">
    <!-- Boolean Enabled Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('enable_paystack', trans("lang.app_setting_enable_paystack"),['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        {!! Form::hidden('enable_paystack', 0, ['id'=>"hidden_enable_paystack"]) !!}
        <div class="col-9 icheck-{{setting('theme_color')}}">
            {!! Form::checkbox('enable_paystack', 1, setting('enable_paystack')) !!}
            <label for="enable_paystack"></label>
        </div>
    </div>
</div>
