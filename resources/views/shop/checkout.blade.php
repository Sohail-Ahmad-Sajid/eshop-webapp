@extends('layouts.app')

@section('content')

<section class="section-heading">
    <div>
        <p class="eyebrow">Almost done</p>
        <h1>Checkout</h1>
    </div>

    <a href="/cart">Back to Cart</a>
</section>

<form method="POST" action="/order" class="checkout-layout">

    @csrf

    <div class="form-card">

        <div class="mb-3">
            <label for="shipping_address" class="form-label">
                Shipping Address
            </label>

            <textarea
                name="shipping_address"
                id="shipping_address"
                class="form-control"
                rows="4"
                required></textarea>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">
                Phone
            </label>

            <input
                type="text"
                name="phone"
                id="phone"
                class="form-control"
                required>
        </div>

        <div class="mb-3">

            <label for="payment_method" class="form-label">
                Payment Method
            </label>

            <select
                name="payment_method"
                id="payment_method"
                class="form-control"
                required>

                <option value="cod">
                    Cash on Delivery
                </option>

                <option value="stripe">
                    Stripe Card
                </option>

            </select>

        </div>

        <!-- Payment Information -->
        <div id="paymentInfo"
             style="display:none;
                    border:1px solid #ddd;
                    border-radius:8px;
                    padding:15px;
                    margin-bottom:20px;
                    background:#f8f9fa;">

            <h4>Stripe Payment Details</h4>

            <p>
                Please transfer the payment to the following account.
            </p>

            <table class="table table-bordered">

                <tr>
                    <th>Account Name</th>
                    <td>Sohail Ahmad Sajid</td>
                </tr>

                <tr>
                    <th>Bank</th>
                    <td>UBL</td>
                </tr>

                <tr>
                    <th>Account Number</th>
                    <td>1234567890123456</td>
                </tr>

                <tr>
                    <th>IBAN</th>
                    <td>PK00ABCD1234567890123456</td>
                </tr>

            </table>

            <div class="mb-3">

                <label for="transaction_id" class="form-label">
                    Transaction ID
                </label>

                <input
                    type="text"
                    name="transaction_id"
                    id="transaction_id"
                    class="form-control"
                    placeholder="Enter your transaction ID">

                <small class="text-danger">
                    Transaction ID is required for Stripe payments.
                </small>

            </div>

        </div>

        <button type="submit" class="btn btn-primary">
            Place Order
        </button>

    </div>

    <aside class="summary-card">

        <h5>Order Summary</h5>

        <table>

            @foreach($cartItems as $item)

                <tr>

                    <td>

                        {{ $item->product->name }}

                        ×

                        {{ $item->quantity }}

                    </td>

                    <td>

                        ${{ number_format($item->total,2) }}

                    </td>

                </tr>

            @endforeach

            <tr class="total-row">

                <td>
                    <strong>Total</strong>
                </td>

                <td>
                    <strong>${{ number_format($cartItems->sum('total'),2) }}</strong>
                </td>

            </tr>

        </table>

    </aside>

</form>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const paymentMethod = document.getElementById("payment_method");
    const paymentInfo = document.getElementById("paymentInfo");
    const transactionId = document.getElementById("transaction_id");

    function togglePayment() {

        if (paymentMethod.value === "stripe") {

            paymentInfo.style.display = "block";

            transactionId.required = true;

        } else {

            paymentInfo.style.display = "none";

            transactionId.required = false;

            transactionId.value = "";

        }

    }

    togglePayment();

    paymentMethod.addEventListener("change", togglePayment);

});

</script>

@endsection