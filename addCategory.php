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

?>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Category</h4>
                </div>
                <div class="card-body">
                    <form action="apiAddCategory.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">

                                <label for="">Name</label>
                                <input type="text" name="name" placeholder="Enter Category Name" class="form-control">
                            </div>
                            <div class="col-md-6">

                                <label for="">Slug</label>
                                <input type="text" name="slug" placeholder="Enter slug" class="form-control">
                            </div>
                            <div class="col-md-12">

                                <label for="">Description</label>
                                <input type="text" name="desc" placeholder="Enter Description" class="form-control">
                            </div>
                            <div class="col-md-12">

                                <label for="">Upload Image</label>
                                <input type="file" name="img" placeholder="Upload Image" class="form-control">
                            </div>
                            <div class="col-md-12">

                                <label for="">Meta Title</label>
                                <input type="text" name="metatitle" placeholder="Meta Title" class="form-control">
                            </div>
                            <div class="col-md-12">

                                <label for="">Meta Description</label>
                                <textarea rows="3" name="metadesc" placeholder="Enter Meta Description" class="form-control"></textarea>
                            </div>
                            <div class="col-md-12">

                                <label for="">Meta Keywords</label>
                                <textarea rows="3" name="metakey" placeholder="Enter Meta Keyword" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">

                                <label for="">Status</label>
                                <input type="checkbox" name="status">
                            </div>
                            <div class="col-md-6">

                                <label for="">Popular</label>
                                <input type="checkbox" name="popular">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="add_category">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'part/a_footer.php'; ?>