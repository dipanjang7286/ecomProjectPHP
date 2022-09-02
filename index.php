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
?>
<?php
include_once 'part/header.php';
include_once 'part/nav.php';
include_once 'db.php';
$conn = $GLOBALS["connection"];
$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
}
// var_dump($result);

?>
<?php include_once 'part/header.php' ?>

<div class="container text-center">
    <h1>Products</h1>
    <a href="categories.php" class="btn btn-warning col-lg-2">Home</a>
    <a href="viewcart.php" class="btn btn-warning col-lg-2">Cart</a>
    <br>
    <br>
    <table class="table">
        <thread>
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Image</th>
                <th>Price</th>
            </tr>
        </thread>
        <form action="addcart.php" method="post">
            <tbody>
                <?php while ($rows = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="select" id="flexCheckChecked">
                            </div>
                        </td>
                        <td><?php echo $rows['name'] ?></td>
                        <td><img src="uploads/<?= $rows['image'] ?> " width="50px" height="50px" alt=""></td>
                        <td><?php echo $rows['selling_price'] ?></td>
                        <td>Quantity</td>
                        <td><input type="text" name="qty" class="form-control col-lg-6"></td>
                        <td><input type="submit" value="Add to Cart" name="addcart" class="btn btn-primary"></td>

                    </tr>


                <?php } ?>


            </tbody>
        </form>
    </table>


</div>

<?php include_once 'part/footer.php'; ?>