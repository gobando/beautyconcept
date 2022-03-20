<div class='btn-group btn-group-sm'>
    @can('walletTransactions.show')
        <a data-toggle="tooltip" data-placement="left" title="{{trans('lang.view_details')}}" href="{{ route('walletTransactions.show', $id) }}" class='btn btn-link'>
            <i class="fas fa-eye"></i> </a> @endcan

    @can('walletTransactions.edit')
        <a data-toggle="tooltip" data-placement="left" title="{{trans('lang.wallet_transaction_edit')}}" href="{{ route('walletTransactions.edit', $id) }}" class='btn btn-link'>
            <i class="fas fa-edit"></i> </a> @endcan

    @can('walletTransactions.destroy') {!! Form::open(['route' => ['walletTransactions.destroy', $id], 'method' => 'delete']) !!} {!! Form::button('<i class="fas fa-trash"></i>', [ 'type' => 'submit', 'class' => 'btn btn-link text-danger', 'onclick' => "return confirm('Are you sure?')" ]) !!} {!! Form::close() !!} @endcan
</div>
