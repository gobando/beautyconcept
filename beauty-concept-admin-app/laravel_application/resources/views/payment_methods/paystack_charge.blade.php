<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PayStack Payment</title>
</head>
<body>
<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<!-- jQuery -->
<script type="application/javascript">

    let handler = PaystackPop.setup({
        key: '{{ setting('paystack_key') }}', // Replace with your public key
        email: '{{$booking->user->email}}',
        amount: '{{  floor($booking->getTotal() * 100) }}',
        ref: '' + Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
        // label: "Optional string that replaces customer email"
        onClose: function () {
            alert('Window closed.');
        },
        callback: function (response) {
            if (response.status === "success") {
                $.ajax({
                    type: 'GET',
                    url: "{!! url('payments/paystack/pay-success',['booking_id' => $booking->id]) !!}/" + response.reference,
                    success: function (result) {
                        console.log(result);
                        window.location.href = "{!! url('payments/paystack') !!}";
                    },
                    error: function (err) {
                    }
                });
            } else {
                window.location.href = "{!! url('payments/failed') !!}";
            }
            // let message = 'Payment complete! Reference: ' + response.reference;
            // alert(message);
        }
    });
    handler.openIframe();
</script>
</body>
</html>
