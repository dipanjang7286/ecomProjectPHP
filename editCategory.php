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
?>
<?php
include_once 'part/a_header.php';
include_once 'db.php';
$conn = $GLOBALS["connection"];
$id = $_GET['id'];
$query = "SELECT * FROM category where id = '$id' ";
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
                            <h4>Edit Category
                                <a href="viewCategory.php" class="btn btn-primary float-end">Back</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="apiAddCategory.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="id" value="<?= $rows['id'] ?>">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $rows['name'] ?>" placeholder="Enter Category Name" class="form-control">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Slug</label>
                                        <input type="text" name="slug" value="<?= $rows['slug'] ?>" placeholder="Enter slug" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Description</label>
                                        <input type="text" name="desc" value="<?= $rows['description'] ?>" placeholder="Enter Description" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Upload Image</label>
                                        <input type="file" name="img" placeholder="Upload Image" class="form-control">
                                        <label for="">Current Image</label>
                                        <img src="uploads/<?= $rows['image'] ?>" width="50px" height="50px" alt="">
                                        <input type="hidden" name="oldimg" value="<?= $rows['image'] ?>">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Meta Title</label>
                                        <input type="text" name="metatitle" value="<?= $rows['meta_title'] ?>" placeholder=" Meta Title" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Meta Description</label>
                                        <textarea rows="3" name="metadesc" placeholder="Enter Meta Description" class="form-control"><?= $rows['meta_desc'] ?></textarea>
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Meta Keywords</label>
                                        <textarea rows="3" name="metakey" placeholder="Enter Meta Keyword" class="form-control"><?= $rows['meta_keywords'] ?></textarea>
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Status</label>
                                        <input type="checkbox" <?= $rows['status'] ? "checked" : "" ?> name="status">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Popular</label>
                                        <input type="checkbox" <?= $rows['popular'] ? "checked" : "" ?> name="popular">
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" name="update_category">Update</button>
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