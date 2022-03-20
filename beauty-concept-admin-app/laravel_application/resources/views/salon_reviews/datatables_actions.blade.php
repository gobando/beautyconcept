<div class='btn-group btn-group-sm'>
    @can('salonReviews.show')
        <a data-toggle="tooltip" data-placement="left" title="{{trans('lang.view_details')}}" href="{{ route('salonReviews.show', $id) }}" class='btn btn-link'>
            <i class="fas fa-eye"></i> </a> @endcan

    @can('salonReviews.edit')
        <a data-toggle="tooltip" data-placement="left" title="{{trans('lang.salon_review_edit')}}" href="{{ route('salonReviews.edit', $id) }}" class='btn btn-link'>
            <i class="fas fa-edit"></i> </a> @endcan

    @can('salonReviews.destroy') {!! Form::open(['route' => ['salonReviews.destroy', $id], 'method' => 'delete']) !!} {!! Form::button('<i class="fas fa-trash"></i>', [ 'type' => 'submit', 'class' => 'btn btn-link text-danger', 'onclick' => "return confirm('Are you sure?')" ]) !!} {!! Form::close() !!} @endcan
</div>
