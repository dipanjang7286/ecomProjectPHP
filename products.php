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
$id = $_GET['category_id'];
$slug = $_GET['slug'];
$query = "SELECT * FROM products WHERE category_id ='$id' AND slug ='$slug' AND  status='1'";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Products</h1>
                <div class="row">
                    <?php if (mysqli_num_rows($result)) { ?>
                        <?php foreach ($rows as $r) { ?>
                            <div class="col-md-3 mb-2">
                                <a href="productView.php?product_id=<?= $r['id'] ?>&slug=<?= $r['slug'] ?>">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <img src="uploads/<?= $r['image'] ?> " alt="Category Image" class="w-100">
                                            <h4 class="text-center"><?= $r['name'] ?></h4>
                                        </div>
                                    </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <h4> <?php echo "No Categories Found"; ?></h4>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include_once 'part/footer.php'; ?>