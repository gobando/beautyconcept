@extends('layouts.auth.default',['width'=>'100%'])
@push('js_lib')
    <title>PayMongo Checkout</title>
    <style>

        .form-card {
            margin: 36px 20px 12px;
        }

        .form-card input,
        .form-card button,
        .form-card textarea {
            padding: 10px 15px 5px 15px;
            border: none;
            border: 1px solid lightgrey;
            border-radius: 6px;
            margin-bottom: 25px;
            margin-top: 2px;
            width: 100%;
            box-sizing: border-box;
            font-family: 'Poppins';
            color: #032416;
            font-size: 14px;
            letter-spacing: 1px
        }

        .form-card input:focus,
        .form-card textarea:focus {
            font-weight: bold;
            border: 1px solid #25b47e;
            outline-width: 0
        }

        .input-group {
            position: relative;
            width: 100%;
            overflow: hidden
        }

        .input-group input {
            position: relative;
            height: 80px;
            margin-left: 1px;
            margin-right: 1px;
            border-radius: 6px;
            padding-top: 30px;
            padding-left: 25px
        }

        .input-group label {
            position: absolute;
            height: 24px;
            background: none;
            border-radius: 6px;
            line-height: 48px;
            font-size: 15px;
            color: gray;
            width: 100%;
            font-weight: 100;
            padding-left: 25px
        }

        input:focus + label {
            color: #25b47e
        }

        .btn-pay {
            background-color: #21ae78;
            height: 60px;
            color: #ffffff !important;
            font-weight: bold
        }

        .btn-pay:hover {
            background-color: #25b47e
        }
    </style>
@endpush
@section('content')
    <form id="checkout_form" class="form-card">
        <div id="errors" class="alert alert-danger d-none" role="alert"></div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="input-group"><input required="required" type="text" id="name" name="Name" placeholder="John Doe"> <label>Name</label></div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="input-group">
                    <input required="required" type="text" id="cr_no" name="card-no" placeholder="0000 0000 0000 0000" minlength="19" maxlength="19">
                    <label>Card Number</label></div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group"><input required="required" type="text" id="exp" name="expdate" placeholder="MM/YY" minlength="5" maxlength="5">
                            <label>Expiry Date</label></div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <input required="required" type="password" id="cvv" name="cvv" placeholder="&#9679;&#9679;&#9679;" minlength="3" maxlength="3">
                            <label>CVC</label></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <button type="submit" class="btn btn-pay placeicon">Pay {!!  getPrice($booking->getTotal()) !!}</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            //For Card Number formatted input
            let cardNum = document.getElementById('cr_no');
            let name = document.getElementById('name');
            let errorsAlert = $('#errors');
            cardNum.onkeyup = function (e) {
                if (this.value == this.lastValue) return;
                let caretPosition = this.selectionStart;
                let sanitizedValue = this.value.replace(/[^0-9]/gi, '');
                let parts = [];

                for (let i = 0, len = sanitizedValue.length; i < len; i += 4) {
                    parts.push(sanitizedValue.substring(i, i + 4));
                }
                for (let i = caretPosition - 1; i >= 0; i--) {
                    let c = this.value[i];
                    if (c < '0' || c > '9') {
                        caretPosition--;
                    }
                }
                caretPosition += Math.floor(caretPosition / 4);

                this.value = this.lastValue = parts.join('-');
                this.selectionStart = this.selectionEnd = caretPosition;
            }

            //For Date formatted input
            let expDate = document.getElementById('exp');

            let parts = [];
            expDate.onkeyup = function (e) {
                if (this.value == this.lastValue) return;
                let caretPosition = this.selectionStart;
                let sanitizedValue = this.value.replace(/[^0-9]/gi, '');
                parts = [];

                for (let i = 0, len = sanitizedValue.length; i < len; i += 2) {
                    parts.push(sanitizedValue.substring(i, i + 2));
                }
                for (let i = caretPosition - 1; i >= 0; i--) {
                    let c = this.value[i];
                    if (c < '0' || c > '9') {
                        caretPosition--;
                    }
                }
                caretPosition += Math.floor(caretPosition / 2);

                this.value = this.lastValue = parts.join('/');
                this.selectionStart = this.selectionEnd = caretPosition;
            }

            let cvv = document.getElementById('cvv');

            $('#checkout_form').submit(function (e) {
                e.preventDefault();
                let cardNumValue = cardNum.value.replaceAll('-', '');
                let expMonth = parseInt(parts[0]);
                let expYear = parseInt(parts[1]);
                let cvvValue = cvv.value;
                let options = {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        Authorization: 'Basic ' + btoa('{{ setting("paymongo_key") }}')
                    },
                    body: JSON.stringify({
                        data: {
                            attributes: {
                                details: {card_number: cardNumValue, exp_month: expMonth, exp_year: expYear, cvc: cvvValue},
                                billing: {name: name.value, email: '{!! $booking->user->email !!}', phone: '{!! $booking->user->phone_number !!}'},
                                type: 'card'
                            }
                        }
                    })
                };
                fetch('https://api.paymongo.com/v1/payment_methods', options)
                    .then(response => response.json())
                    .then(function (response) {
                        console.log(response);
                        if (response.errors === undefined) {
                            window.location.href = "{!! url('payments/paymongo/processing',['booking_id' => $booking->id]) !!}/" + response.data.id;
                        } else {
                            let errors = response.errors.map(a => a.detail.replaceAll('details.', '').replaceAll('_', ' '));
                            errors = errors.join('<br><br>')
                            errorsAlert.html(errors);
                            errorsAlert.removeClass('d-none');
                        }
                    })
                    .catch(function (err) {
                        errorsAlert.html(err);
                        errorsAlert.removeClass('d-none');
                        window.location.href = "{!! url('payments/failed') !!}";
                    });
            });
        })
    </script>
@endpush
