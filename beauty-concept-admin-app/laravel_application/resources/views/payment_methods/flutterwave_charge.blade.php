<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flutterwave Payment</title>
</head>
<body>
<!-- jQuery -->
<script src="{!!asset('vendor/jquery/jquery.min.js')!!}"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script type="application/javascript">
    FlutterwaveCheckout({
        public_key: '{!! setting('flutterwave_key') !!}',
        tx_ref: "RX" + Math.floor((Math.random() * 1000000000) + 1),
        amount: {!! $booking->getTotal()!!},
        currency: '{!!setting("default_currency_code")!!}',
        country: "US",
        payment_options: " ",
        customer: {
            email: '{!!$booking->user->email!!}',
            phone_number: '{!!$booking->user->phone_number!!}',
            name: '{!! $booking->user->name !!}',
        },
        callback: function (data) {
            $.ajax({
                type: 'GET',
                url: "{!! url('payments/flutterwave/pay-success',['booking_id' => $booking->id]) !!}/" + data.transaction_id,
                success: function (result) {
                    console.log(result);
                    window.location.href = "{!! url('payments/flutterwave') !!}";
                },
            });
        },
        onclose: function () {
            // close modal
        },
        customizations: {
            title: '{!! $booking->salon->name !!}',
            description: '{!! setting("app_name") !!}',
            logo: '{!! $booking->salon->getFirstMediaUrl("image","thumb") !!}',
            language: '{!! setting("mobile_language") !!}',
            color: '{!! setting("main_color") !!}',
        },
    });
</script>
</body>
</html>
