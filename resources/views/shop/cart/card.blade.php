@extends('master')
@section('body')
        <div class="container">
            {{csrf_field()}}
            @if (session()->has('success_message'))
                <div class="alert alert-success">
                    {{ session()->get('success_message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-success">
                    {{ session()->get('error') }}
                </div>
            @endif
            <div class="col-md-7">
                <form action="{{route('card.checkout')}}" method="POST" id="payment-form">
                    {{csrf_field()}}
                    <div class="form-group">
                        <h5>You have to pay: {{$total}}</h5>
                    <label for="first_name">First name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                    <label for="second_name">Second name</label>
                    <input type="text" id="second_name" name="second_name" class="form-control" required>
                    <input type="hidden"  id="name_on_card" name="name_on_card" value="{{auth()->user()->name}}" required>
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" required>
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" class="form-control" required>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control" required>
                    <label for="second_address">Delivery address</label>
                    <input type="text" id="second_address" name="second_address" class="form-control" required>
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" name="postcode" class="form-control" required>
                    <label for="phone_number">Phone number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                    <label for="card-element">Credit or debit card</label>
                    <div id="card-element">
                    </div>
                    <div id="card-errors" role="alert"></div>
                    </div>
                    <button>Submit Payment</button>
                </form>
            </div>
        </div>

        <script>
            // Create a Stripe client.
            var stripe = Stripe('pk_test_aQnNOphfY95Tep3X1OapJp7y');

            // Create an instance of Elements.
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style,
                hidePostalCode:true,
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });
            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
    </script>

@endsection()