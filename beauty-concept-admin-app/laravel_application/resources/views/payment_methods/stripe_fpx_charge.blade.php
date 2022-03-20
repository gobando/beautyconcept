@extends('layouts.auth.default')
@push('js_lib')
    <title>Stripe FPX Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
@endpush
@section('content')
    <div class="card-body login-card-body">
        <form id="payment-form">
            <div class="input-group mb-3">
                <input value="{{ $booking->user->name }}" type="text" class="form-control" id="name" name="name" placeholder="{{ __('auth.name') }}" aria-label="{{ __('auth.name') }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input value="{{ $booking->user->email }}" type="email" class="form-control" id="email" name="email" placeholder="{{ __('auth.email') }}" aria-label="{{ __('auth.email') }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
            </div>

            <div class="input-group mb-3">
                <div id="fpx-bank-element" class="form-control">
                </div>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                </div>
            </div>

            <div class="mb-2">
                <button type="submit" class="btn btn-primary btn-block">Pay {!!  getPrice($booking->getTotal()) !!} </button>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const stripe = Stripe(' {{setting('stripe_fpx_key')}} ');
            const elements = stripe.elements();
            const fpxBank = elements.create('fpxBank', {
                accountHolderType: 'individual',
                style: {
                    base: {
                        padding: '6px 5px',
                        fontSize: '16px',
                        lineHeight: 0.8
                    },
                },
            })
            fpxBank.mount('#fpx-bank-element');
            const form = document.querySelector('#payment-form');
            const nameInput = document.querySelector('#name');
            const emailInput = document.querySelector('#email');
            const additionalData = {
                name: nameInput.value,
                email: emailInput.value,
            }
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const clientSecret = '{{ $intent->client_secret }}';
                stripe.confirmFpxPayment(clientSecret, {
                    payment_method: {
                        fpx: fpxBank,
                        billing_details: additionalData
                    },
                    // Return URL where the customer should be redirected after the authorization
                    return_url: "{!! url('payments/stripe-fpx/pay-success',['booking_id' => $booking->id]) !!}",
                }).then((result) => {
                    if (result.error) {
                        // Inform the customer that there was an error.
                        $(form).prepend("<div class= 'alert alert-danger'> " + result.error.message + " </div>")
                    }
                });
            });
        });
    </script>
@endpush
