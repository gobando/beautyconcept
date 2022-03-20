<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{setting('app_name')}} | {{setting('app_short_description')}}</title>
    <link rel="icon" type="image/png" href="{{$app_logo ?? ''}}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,600&display=fallback">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <i class="fas fa-exclamation-circle text-danger"></i>
    </div>
    <div class="card shadow-sm">
        <div class="card-body login-card-body text-center">
            <h1 class="error-code display-3 text-danger">{{$code}}</h1>
            <p class="my-3">{!! $message !!}</p>
        </div>
        <div class="btn-group w-100">
            <a class="btn btn-default w-50 " href="{{url()->previous()}}"> <i class="fas fa-arrow-circle-left mx-3"></i>{{trans('error.back')}}</a>
            <a class="btn btn-default w-50 " href="{{route('users.profile')}}"> <i class="fas fa-user-circle mx-3"></i>{{trans('error.back_to_home')}}</a>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>

