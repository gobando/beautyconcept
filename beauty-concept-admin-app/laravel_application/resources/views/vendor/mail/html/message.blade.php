@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
@if(isset($app_logo))
<img src="{!!$app_logo!!}" class="logo" alt="{{ setting('app_name',config('app.name')) }}">
@else
{{ setting('app_name',config('app.name')) }}
@endif
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ setting('app_name',config('app.name')) }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
