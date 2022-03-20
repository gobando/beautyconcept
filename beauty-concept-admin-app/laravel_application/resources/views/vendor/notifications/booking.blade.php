@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
    {{ $line }}

@endforeach
@component('mail::panel')
    <tr>
        <td><b>{{$booking->e_service->name}}</b></td>
        <td class="text-right">{{__('lang.by')}} {{$booking->salon->name}}</td>
    </tr>
@endcomponent
@component('mail::panel')
    <tr>
        <td><b>{{__('lang.booking_address')}}</b></td>
        <td class="text-right"><small>{{$booking->address->address}}</small></td>
    </tr>
@endcomponent
@component('mail::panel')
    <tr>
<td><b>{{__('lang.booking_booking_at')}}</b></td>
<td class="text-right"><small>{{$booking->booking_at}}</small></td>
</tr>
<tr>
<td><b>{{__('lang.booking_start_at')}}</b></td>
<td class="text-right"><small>{{$booking->start_at ?: '-'}}</small></td>
</tr>
<tr>
<td><b>{{__('lang.booking_ends_at')}}</b></td>
<td class="text-right"><small>{{$booking->ends_at ?: '-'}}</small></td>
</tr>
@endcomponent
@component('mail::panel')
<tr>
<td><b>{{__('lang.booking_total')}}</b></td>
<td class="text-right"><h3 class="text-right">{!! getPrice($booking->getTotal()) !!}</h3></td>
</tr>
<tr>
<td><b>{{__('lang.payment_status')}}</b></td>
<td class="text-right"><small>{{empty(!$booking->payment) ? $booking->payment->paymentStatus->status : '-'}}</small></td>
</tr>
<tr>
<td><b>{{__('lang.payment_method')}}</b></td>
<td class="text-right"><small>{{empty(!$booking->payment) ? $booking->payment->paymentMethod->name : '-'}}</small></td>
</tr>
@endcomponent
{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
case 'success':
case 'error':
$color = $level;
break;
default:
$color = 'primary';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ setting('app_name',config('app.name')) }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
"If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser:',
[
'actionText' => $actionText,
]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
