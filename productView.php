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
include_once 'part/header.php';
include_once 'db.php';
$conn = $GLOBALS["connection"];
$id = $_GET['product_id'];
$slug = $_GET['slug'];
$query = "SELECT * FROM products WHERE id ='$id' AND slug ='$slug' AND  status='1'";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();
}
?>
<div class="bg-light py-4">
    <div class="container product-data mt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="shadow">
                    <img src="uploads/<?= $rows['image'] ?> " alt="Product Image" class="w-100">
                </div>
            </div>
            <div class="col-md-8">
                <h4 class="fw-bold"><?= $rows['name'] ?></h4>
                <hr>
                <p><?= $rows['small_desc'] ?></p>
                <h6>Product Description:</h6>
                <p></p><?= $rows['description']; ?></p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Price <span class="text-success fw-bold"><?= $rows['selling_price'] ?></span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5>Price <s class="text-danger"><?= $rows['original_price'] ?></s></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-3" style="width: 130px;">
                            <button class="input-group-text decrement-btn">-</button>
                            <input type="text" class="form-control text-center bg-white qty" value="1" disabled>
                            <button class="input-group-text increment-btn">+</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary px-4 addtocart" value="<?= $rows['id'] ?>"><i class="fa fa-shopping-cart me-2"></i> Add to cart</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-danger px-4"><i class="fa fa-heart me-2"></i> Add to whishlist</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include_once 'part/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.serializeObject/2.0.3/jquery.serializeObject.min.js" integrity="sha512-DNziaT2gAUenXiDHdhNj6bfk1Ivv72gpxOeMT+kFKXB2xG/ZRtGhW2lDJI9a+ZNCOas/rp4XAnvnjtGeMHRNyg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('.increment-btn').click(function(e) {
            e.preventDefault();
            var qty = $(this).closest('.product-data').find('.qty').val();
            var value = parseInt(qty, 10);
            value = isNaN(value) ? 0 : value;
            if (value < 5) {
                value++;
                $(this).closest('.product-data').find('.qty').val(value);
            }
        })
    });
    $(document).ready(function() {
        $('.decrement-btn').click(function(e) {
            e.preventDefault();
            var qty = $(this).closest('.product-data').find('.qty').val();
            var value = parseInt(qty, 10);
            value = isNaN(value) ? 0 : value;
            if (value > 1) {
                value--;
                $(this).closest('.product-data').find('.qty').val(value);
            }
        })
        // $('.decrement-btn').click(function(e) {
        //     e.preventDefault();
        //     var qty = $(this).closest('.product-data').find('.qty').val();
        //     var value = parseInt(qty, 10);
        //     value = isNaN(value) ? 0 : value;
        //     if (value > 1) {
        //         value--;
        //         $(this).closest('.product-data').find('.qty').val(value);
        //     }
        // })
        $('.addtocart').click(function(e) {
            e.preventDefault();
            var qty = $(this).closest('.product-data').find('.qty').val();
            var product_id = $(this).val();
            // alert(product_id);
            $.ajax({
                method: "POST",
                url: "addcart.php",
                data: {
                    "product_id": product_id,
                    "product_qty": qty,
                    "scope": "add"
                },
                success: function(response) {
                    window.location.href = 'viewcart.php'
                }
            })
        })
    });
</script>