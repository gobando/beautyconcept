<div class='btn-group btn-group-sm'>
    @can('earnings.create')
        <a data-toggle="tooltip" data-placement="left" title="{{trans('lang.salon_payout_create')}}" href="{{ isset($salon_id) ? route('salonPayouts.create', $salon_id ) : "#" }}" class='btn btn-link'>
            <i class="fas fa-money-bill-wave"></i> </a>
    @endcan

</div>
