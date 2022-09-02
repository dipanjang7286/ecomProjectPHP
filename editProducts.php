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
$query = "SELECT * FROM category";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {

    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}
// var_dump($rows);
////
$prod_id=$_GET['id'];
$products_query = "SELECT * FROM products WHERE id='$prod_id'";
$stmt = $conn->prepare($products_query);
if ($stmt->execute()) {

    $products_result = $stmt->get_result();
    $products_rows = $products_result->fetch_assoc();
}

?>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['id'])) { ?>
                <?php if (mysqli_num_rows($products_result)) { ?>
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Product
                                <a href="viewProducts.php" class="btn btn-primary float-end">Back</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="apiAddCategory.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Select Category</label>
                                        <select name="cat_id" class="form-select">
                                            <option selected>Select Category</option>
                                            <?php foreach ($rows as $r) { ?>
                                                <option value="<?= $r['id'] ?>" <?= $products_rows['category_id'] == $r['id'] ? 'selected' : '' ?>><?= $r['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $products_rows['name'] ?>" placeholder="Enter Category Name" class="form-control">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Slug</label>
                                        <input type="text" name="slug" value="<?= $products_rows['slug'] ?>" placeholder=" Enter slug" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Small Description</label>
                                        <input type="text" name="small_desc" value="<?= $products_rows['small_desc'] ?>" placeholder="Enter Small Description" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Description</label>
                                        <input type="text" name="desc" value="<?= $products_rows['description'] ?>" placeholder="Enter Description" class="form-control">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Original Price</label>
                                        <input type="text" name="o_price" value="<?= $products_rows['original_price'] ?>" placeholder=" Enter Original Price" class="form-control">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="">Selling Price</label>
                                        <input type="text" name="s_price" value="<?= $products_rows['selling_price'] ?>" placeholder="Enter selling Price" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Upload Image</label>
                                        <input type="file" name="img" placeholder="Upload Image" class="form-control">
                                        <label for="">Current Image</label>
                                        <img src="uploads/<?= $products_rows['image'] ?>" width="50px" height="50px" alt="">
                                        <input type="hidden" name="oldimg" value="<?= $products_rows['image'] ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">

                                            <label for="">Quantity</label>
                                            <input type="text" name="qty" value="<?= $products_rows['quantity'] ?>" placeholder="Enter Quantity" class="form-control">
                                        </div>
                                        <div class="col-md-3">

                                            <label for="">Status</label>
                                            <input type="checkbox" <?= $products_rows['status'] ? "checked" : " " ?> name="status">
                                        </div>
                                        <div class="col-md-3">

                                            <label for="">Trending</label>
                                            <input type="checkbox" <?= $products_rows['trending'] ? "checked" : " " ?> name="trending">
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Meta Title</label>
                                        <input type="text" name="metatitle" value="<?= $products_rows['meta_title'] ?>" placeholder="Meta Title" class="form-control">
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Meta Keywords</label>
                                        <textarea rows="3" name="metakey" placeholder="Enter Meta Keyword" class="form-control"><?= $products_rows['meta_keyword'] ?></textarea>
                                    </div>
                                    <div class="col-md-12">

                                        <label for="">Meta Description</label>
                                        <textarea rows="3" name="metadesc" placeholder="Enter Meta Description" class="form-control"><?= $products_rows['meta_desc'] ?></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="hidden" value="<?= $products_rows['id'] ?>" name="id">
                                        <button type="submit" class="btn btn-primary" name="update_product">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php echo "Products not Found"; ?>
                <?php } ?>
            <?php } else { ?>
                <?php echo "Id Missing from url" ?>

            <?php } ?>
        </div>
    </div>
</div>

<?php include_once 'part/a_footer.php'; ?>