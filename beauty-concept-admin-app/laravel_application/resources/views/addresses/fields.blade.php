@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="d-flex flex-column col-sm-12 col-md-6">
    <!-- Description Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('description', trans("lang.address_description"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('description', null,  ['class' => 'form-control','placeholder'=>  trans("lang.address_description_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.address_description_help") }}
            </div>
        </div>
    </div>

    <!-- Address Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('address', trans("lang.address_address"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::text('address', null,  ['id'=>'address-input','class' => 'form-control map-input','placeholder'=>  trans("lang.address_address_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.address_address_help") }}
            </div>
        </div>
    </div>

</div>
<div class="d-flex flex-column col-sm-12 col-md-6">

    <!-- Latitude Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('latitude', trans("lang.address_latitude"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::number('latitude', null,  ['class' => 'form-control','step'=>'any', 'placeholder'=>  trans("lang.address_latitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.address_latitude_help") }}
            </div>
        </div>
    </div>

    <!-- Longitude Field -->
    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
        {!! Form::label('longitude', trans("lang.address_longitude"), ['class' => 'col-md-3 control-label text-md-right mx-1']) !!}
        <div class="col-md-9">
            {!! Form::number('longitude', null,  ['class' => 'form-control','step'=>'any', 'placeholder'=>  trans("lang.address_longitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.address_longitude_help") }}
            </div>
        </div>
    </div>
</div>
<div class="col-12" style="height:400px; ">
    <div style="width: 100%; height: 100%" id="map"></div>
</div>
@if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
    <!-- 'Boolean Default Field' -->
    <div class="d-flex flex-row justify-content-between align-items-center">
        {!! Form::label('default', trans("lang.address_default"),['class' => 'control-label my-0 mx-3']) !!} {!! Form::hidden('default', 0, ['id'=>"hidden_default"]) !!}
        <span class="icheck-{{setting('theme_color')}}">
            {!! Form::checkbox('default', 1, null) !!} <label for="default"></label> </span>
    </div>
    <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
        <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.address')}}</button>
    <a href="{!! route('addresses.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initializeGoogleMaps"></script>
@endpush
