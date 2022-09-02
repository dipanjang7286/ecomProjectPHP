<?php
// session_start();
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
?>
<?php
include_once 'part/a_header.php';
include_once 'db.php';
$conn = $GLOBALS["connection"];
$query = "SELECT * FROM users";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}
?>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 align="center">Admin Page</h2>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>User Name</th>
                        <th>User Type</th>
                        <th>Date</th>
                        <th>Upadte</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <form action="api.php" method="post">
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($rows as $r) { ?>
                            <tr>
                                <td><?php echo $no++ ?></td>

                                <td><?php echo $r['fname'] ?></td>
                                <!-- <td><?php //echo $rows['quantity'] 
                                            ?></td> -->
                                <td><?php echo $r['lname'] ?></td>
                                <td><?php echo $r['uname'] ?></td>
                                <td><?php echo $r['user_type'] ?></td>
                                <td><?php echo $r['dt'] ?></td>
                                <td>
                                    <a href="editUser.php?id=<?= $r['id']; ?>" class="btn btn-success">Update</a>
                                </td>
                                <td>
                                    <form action="api.php" method="POST">
                                        <input type="hidden" name="user_id" value="<?php echo $r['id'] ?>">
                                        <button type="submit" class="btn btn-primary" name="delete_user">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </form>
            </table>

        </div>
    </div>
</div>
<?php include_once 'part/a_footer.php'; ?>