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
$query = "SELECT * FROM category WHERE status = '1'";
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
                <h1>Our Categories</h1>
                <div class="row">
                    <?php if (mysqli_num_rows($result)) { ?>
                        <?php foreach ($rows as $r) { ?>
                            <div class="col-md-3 mb-2">
                                <a href="products.php?category_id=<?= $r['id'] ?>&slug=<?= $r['slug'] ?>">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <img src="uploads/<?= $r['image'] ?> " alt="Category Image" class="w-100">
                                            <h4 class="text-center"><?= $r['name'] ?></h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <?php echo "No Categories Found"; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include_once 'part/footer.php'; ?>
