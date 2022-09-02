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
if (isset($_COOKIE['uname']) &&  $decoded_array['data']->user_type == 'admin') {
} else {
    header('location:login.php');
}

include_once 'part/a_header.php';
include_once 'db.php';
$conn = $GLOBALS["connection"];
$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card-header">
                Products
            </div>
            <div class="card-body">
                <table class="table table-borderd">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Upadte</th>
                            <th>Delete</th>
                            <!-- <th>Delete</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r) { ?>
                            <tr>
                                <td><?php echo $r['id'] ?></td>
                                <td><?php echo $r['name'] ?></td>
                                <td>
                                    <img src="uploads/<?= $r['image'] ?> " width="50px" height="50px" alt="<?= $r['name'] ?>">
                                </td>
                                <td><?php echo $r['status'] == '0' ? 'Out of stock' : 'In stock' ?></td>
                                <td>
                                    <a href="editProducts.php?id=<?= $r['id']; ?>" class="btn btn-success">Update</a>
                                </td>
                                <td>
                                    <form action="apiAddCategory.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $r['id'] ?>">
                                        <button type="submit" class="btn btn-primary" name="delete_product">Delete</button>
                                    </form>
                                </td>
                                <!-- <td><input type="submit" value="Delete" name="delete" class="btn btn-primary"></td> -->
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'part/a_footer.php'; ?>