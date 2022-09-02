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
$id = $_GET['id'];
$query = "SELECT * FROM users where id = '$id' ";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['id'])) { ?>
                <?php if (mysqli_num_rows($result)) { ?>

                    <div class="card">
                        <div class="card-header">
                            <h4>Edit User</h4>
                        </div>
                        <div class="card-body">
                            <form action="api.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="id" value="<?= $rows['id'] ?>">
                                        <label for="">First Name</label>
                                        <input type="text" name="fname" value="<?= $rows['fname'] ?>" placeholder="Enter First Name" class="form-control">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Last Name</label>
                                        <input type="text" name="lname" value="<?= $rows['lname'] ?>" placeholder="Enter Last Name" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">User Name</label>
                                        <input type="text" name="uname" value="<?= $rows['uname'] ?>" placeholder="Enter User Name" class="form-control">
                                    </div>

                                    <div class="col-md-12">

                                        <label for="">User Type</label>
                                        <input type="text" name="user_type" value="<?= $rows['user_type'] ?>" placeholder="Enter User Name" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" name="update_user">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php echo "Category not Found"; ?>
                <?php } ?>
            <?php } else { ?>
                <?php echo "Id Missing from url" ?>

            <?php } ?>
        </div>
    </div>
</div>

<?php include_once 'part/a_footer.php'; ?>
