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
$query = "SELECT c.id AS cid ,c.product_id,c.quantity,p.name,p.selling_price,p.image,c.is_checked_out AS is_checked_out  FROM cart c ,products p WHERE c.product_id=p.id AND c.user_id='$id' ORDER BY c.id DESC";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    
    $row_cnt = $result->num_rows;
    // $cart_id=$rows['cid'];
    // var_dump($cart_id);
    // die();
}
// $query1 = "SELECT *  FROM cart WHERE user_id='$id' AND id='$cart_id'";
// $stmt = $conn->prepare($query);
// if ($stmt->execute()) {

//     $result = $stmt->get_result();
//     $row = $result->fetch_assoc();
//     // var_dump($row);
// }   


include_once 'part/header.php';
include_once 'part/nav.php';
?>
<?php if ($row_cnt>0) { ?>
    <div class="py_5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <h5>Products</h5>
                        </div>
                        <div class="col-md-2">
                            <h5>Price</h5>
                        </div>
                        <div class="col-md-2">
                            <h5>Quantity</h5>
                        </div>
                        <div class="col-md-2">
                            <h5>Action</h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="py_5">
    <div class="container">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($row_cnt>0) { ?>
                        <?php foreach ($rows as $r) { ?>
                            <div class="card shadow-sm mb-3 product-data">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="uploads/<?= $r['image']; ?>" alt="Image" width="80px">
                                    </div>
                                    <div class="col-md-3">
                                        <h5><?= $r['name']; ?></h5>
                                    </div>
                                    <div class="col-md-2">
                                        <h5><?= $r['selling_price'] * $r['quantity']; ?></h5>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" class="product-id" value="<?= $r['product_id'] ?>">
                                        <div class="input-group mb-3" style="width: 130px;">
                                            <button class="input-group-text decrement-btn updateQty">-</button>
                                            <input type="text" class="form-control text-center bg-white qty" value="<?= $r['quantity']; ?>" disabled>
                                            <button class="input-group-text increment-btn updateQty">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-danger btn-sm remove-cart" value="<?= $r['cid'] ?>">Remove</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="float-end">
                            <a href="checkout.php" class="btn btn-outline-primary">Checkout</a>
                        </div>
                    <?php } else { ?>
                        <h5><?php echo "your cart is empty"; ?></h5>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'part/footer.php';
?>

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
        $(document).on('click', '.updateQty', function() {
            var qty = $(this).closest('.product-data').find('.qty').val();
            var product_id = $(this).closest('.product-data').find('.product-id').val();
            // alert(qty);
            $.ajax({
                method: "POST",
                url: "addcart.php",
                data: {
                    "product_id": product_id,
                    "product_qty": qty,
                    "scope": "update"
                },
                success: function(response) {
                    // console.log(response);
                    window.location.href = 'viewcart.php'
                }
            })
        })
        $(document).on('click', '.remove-cart', function() {
            var cart_id = $(this).val();
            // alert(cart_id);
            $.ajax({
                method: "POST",
                url: "addcart.php",
                data: {
                    "cart_id": cart_id,
                    "scope": "remove"
                },
                success: function(response) {
                    window.location.href = 'viewcart.php'
                }
            })
        })
    });
</script>