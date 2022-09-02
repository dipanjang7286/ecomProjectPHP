<?php
require "vendor/autoload.php";

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!isset($_COOKIE['uname']) || !isset($_COOKIE['jwt'])) {
    header('location:login.php');
}
$key = 'example_key';
$decoded = JWT::decode($_COOKIE['jwt'], new Key($key, 'HS256'));
$decoded_array = (array) $decoded;
// var_dump($decoded_array);
// var_dump ($decoded_array['data']->user_type);
if (isset($_COOKIE['uname']) &&  $decoded_array['data']->user_type == 'user') {
} else {
    header('location:login.php');
}
include_once 'db.php';
$conn = $GLOBALS["connection"];
$id = $decoded_array['data']->id;
$fname = $decoded_array['data']->fname;
$query = "SELECT c.id AS cid ,c.product_id,c.quantity,p.name,p.selling_price,p.image  FROM cart c ,products p WHERE c.product_id=p.id AND c.user_id='$id' ORDER BY c.id DESC";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    // var_dump($rows);
}
include_once 'part/header.php';
include_once 'part/nav.php';
?>

<div class="py_5">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h5>Besic Details</h5>
                <hr>
            </div>
            <div class="col-md-5">
                <h5>Order Details</h5>
                <hr>
                <div class="row align-items-center">
                    <!-- <div class="col-md-3">
                        <h5>Products</h5>
                    </div>
                    <div class="col-md-3">
                        <h5>Name</h5>
                    </div>
                    <div class="col-md-3">
                        <h5>Quantity</h5>
                    </div>
                    <div class="col-md-3">
                        <h5>Price</h5>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

<div class="py_5">
    <div class="container">
        <div class="card">
            <div class="card-body shadow">
                <!-- placeOrder.php action="placeOrder.php" method="POST" -->
                <form id="payment-form">
                    <div class="row" id="payment-element" class="form-control">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-6 mb-3">

                                    <label for="" class="fw-bold">First Name</label>
                                    <input type="text" name="fname" required placeholder="First Name" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">

                                    <label for="" class="fw-bold">Last Name</label>
                                    <input type="text" name="lname" required placeholder="Last Name" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">

                                    <label for="" class="fw-bold">Contact no:</label>
                                    <input type="text" name="cno" required placeholder="Contact no" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">

                                    <label for="" class="fw-bold">Email</label>
                                    <input type="email" name="email" required placeholder="Email" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">

                                    <label for="" class="fw-bold">Pincode</label>
                                    <input type="text" name="pin" required placeholder="Pincode" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">

                                    <label for="" class="fw-bold">Address</label>
                                    <textarea name="address" required placeholder="Address" class="form-control" rows="5"></textarea>
                                </div>
                                <!-- <div class="col-md-12 mb-3">
                                    a Stripe Element will be inserted here.
                                    <label for="">Card Details</label>
                                    <div id="card-element" class="form-control col-md-12"></div>
                                </div>

                                <div id=" card-errors" role="alert"></div> -->
                            </div>
                        </div>
                        <div class="col-md-5 price-data">

                            <?php
                            $totalPrice = 0;
                            foreach ($rows as $r) { ?>
                                <div class="card shadow-sm mb-3 product-data">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <img src="uploads/<?= $r['image']; ?>" alt="Image" width="80px">
                                        </div>
                                        <div class="col-md-3">
                                            <h5><?= $r['name']; ?></h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5><?= $r['selling_price'] ?></h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5>* <?= $r['quantity']; ?></h5>
                                        </div>
                                    </div>
                                </div>

                            <?php
                                $totalPrice += $r['selling_price'] * $r['quantity'];
                            }
                            ?>
                            <hr>
                            <h5>Total Price: <span class="float-end fw-bold"><?= $totalPrice ?></span></h5>
                            <div class="">
                                <input type="hidden" name="status" value="0">
                                <input type="hidden" name="price" id="price" value="<?= $totalPrice ?>">
                                <input type="hidden" name="id" id="id" value="<?= $id ?>">

                                <button type="submit" name="placeorder" id="btn" class="btn btn-primary w-100">Place Order</button>
                                <div id="payment-message" class="hidden"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- <div>
     <form id='payment-form' style="width: 400px; margin:auto;">
        <label>
            Card details
            placeholder for Elements
            <div id="card-element"></div>
        </label>
        <button type="submit">Submit Payment</button>
    </form>

</div> -->


<?php
include_once 'part/footer.php';
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $(".btn").on('click', function(e) {
            e.preventDefault();
            var price = $(this).closest('.price-data').find('#price').val();
            var id = $(this).closest('.price-data').find('#id').val();
            // alert(id);
            var dt = '&price=' + price + '&user_id=' + id;
            var url = 'checkout_card.php?' + dt;

            $.ajax({
                url: url,
                method: 'GET',
                success: function() {
                    window.location.href = url;
                }
            });


        });

    });
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51IbNFFDfmUqTjyEZRDk58XAUvAy7aW2Tu2N3BgD4YVA1WUJ8SgRUnFp5OTElIsgEYj57AWQgyptQYSnfj6G69i9D00247PrZcH');

    var elements = stripe.elements();

    // Set up Stripe.js and Elements to use in checkout form
    var style = {
        base: {
            color: "#32325d",
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#aab7c4"
            }
        },
        invalid: {
            color: "#fa755a",
            iconColor: "#fa755a"
        },
    };

    var cardElement = elements.create('card', {
        style: style
    });
    cardElement.mount('#card-element');
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        // We don't want to let default form submission happen here,
        // which would refresh the page.
        event.preventDefault();

        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                // Include any additional collected billing details.
                name: "test",
            },
        }).then(stripePaymentMethodHandler);
    });

    function stripePaymentMethodHandler(result) {
        console.log(result.paymentMethod.id)
        if (result.error) {
            // Show error in payment form
        } else {
            // Otherwise send paymentMethod.id to your server (see Step 4)
            fetch('placeOrder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_method_id: result.paymentMethod.id,
                })
            }).then(function(result) {
                // Handle server response (see Step 4)
                result.json().then(function(json) {
                    handleServerResponse(json);
                })
            });
        }
    }

    function handleServerResponse(response) {
        if (response.error) {
            // Show error from server on payment form
        } else if (response.requires_action) {
            // Use Stripe.js to handle required card action
            stripe.handleCardAction(
                response.payment_intent_client_secret
            ).then(handleStripeJsResult);
        } else {
            // Show success message
        }
    }

    function handleStripeJsResult(result) {
        if (result.error) {
            // Show error in payment form
        } else {
            // The card action has been handled
            // The PaymentIntent can be confirmed again on the server
            fetch('placeOrder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_intent_id: result.paymentIntent.id
                })
            }).then(function(confirmResult) {
                return confirmResult.json();
            }).then(handleServerResponse);
        }
    }
</script> -->