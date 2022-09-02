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
$query = "SELECT *  FROM orders WHERE user_id='$id' ORDER BY id DESC";
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

<div class="py_5">
    <div class="container">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-borderd table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tracking No</th>
                                <th>Price</th>
                                <th>Order Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($row_cnt > 0) { ?>
                                <?php foreach ($rows as $r) { ?>
                                    <tr>
                                        <td><?= $r['id'] ?></td>
                                        <td><?= $r['tracking_id'] ?></td>
                                        <td><?= $r['total_price'] ?></td>
                                        <td><?= $r['created_at'] ?></td>
                                        <td>
                                            <a href="" class="btn btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">No orders Found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'part/footer.php';
?>