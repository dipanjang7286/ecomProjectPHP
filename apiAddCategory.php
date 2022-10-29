<?php
include_once 'db.php';
$conn = $GLOBALS["connection"];
////// add category
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $desc = $_POST['desc'];
    $metaTitle = $_POST['metatitle'];
    $metaDesc = $_POST['metadesc'];
    $metaKey = $_POST['metakey'];
    $status = isset($_POST['status']) ? '1' : '0';
    $popular = isset($_POST['popular']) ? '1' : '0';

    $img = $_FILES['img']['name'];
    $path = "uploads";
    $image_ext = pathinfo($img, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    $query = "INSERT INTO category (name,slug,description,meta_title,meta_desc,meta_keywords,status,popular,image) values(?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sssssssss", $name, $slug, $desc, $metaTitle, $metaDesc, $metaKey, $status, $popular, $filename);
        if ($stmt->execute()) {
            // echo "No of records inserted : " . $conn->affected_rows;
            // header("location:login.php");
            // var_dump('adada');
            move_uploaded_file($_FILES['img']['tmp_name'], $path . '/' . $filename);
            header('location: addCategory.php');
        } else {
            echo $conn->error;
        }
    } else {
        echo $conn->error;
    }
}

//edit category

if (isset($_POST['update_category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $desc = $_POST['desc'];
    $metaTitle = $_POST['metatitle'];
    $metaDesc = $_POST['metadesc'];
    $metaKey = $_POST['metakey'];
    $status = isset($_POST['status']) ? '1' : '0';
    $popular = isset($_POST['popular']) ? '1' : '0';

    $img = $_FILES['img']['name'];
    $old_img = $_POST['oldimg'];
    if ($img != "") {
        // $update_filename = $img;
        $image_ext = pathinfo($img, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $img;
    } else {
        $update_filename = $old_img;
    }
    $path = "uploads";

    $query = "UPDATE category SET name = ? ,slug = ? ,description = ? ,meta_title = ? ,meta_desc = ? ,meta_keywords =? ,status =? ,popular = ?,image=? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sssssssssi", $name, $slug, $desc, $metaTitle, $metaDesc, $metaKey, $status, $popular, $update_filename, $id);
        if ($stmt->execute()) {
            // echo "No of records inserted : " . $conn->affected_rows;
            // header("location:login.php");
            // var_dump('adada');
            if ($img = $_FILES['img']['name'] != "") {

                move_uploaded_file($_FILES['img']['tmp_name'], $path . '/' . $update_filename);
                if (file_exists("uploads/" . $old_img)) {
                    unlink("uploads/" . $old_img);
                }
            }
            // header("location:editCategory.php?id=$id");
            header("location:viewCategory.php");
        } else {
            echo $conn->error;
        }
    } else {
        echo $conn->error;
    }
}

///////delete category
$con = mysqli_connect("localhost", "root", "", "project");
if (isset($_POST['delete_category'])) {
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);
    $select_query = "SELECT * FROM category WHERE id = '$category_id'"; //Deleting image
    $select_query_run = mysqli_query($con, $select_query);
    $category_data = mysqli_fetch_array($select_query_run);
    $image = $category_data['image'];
    $delete_query = "DELETE FROM category WHERE id='$category_id'"; // Deleting data
    $delete_query_run = mysqli_query($con, $delete_query);
    if ($delete_query_run) {
        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }
        header("location:viewCategory.php");
    } else {
        // header("location:viewCategory.php");
        echo "Something went Wrong";
    }
}
// if (isset($_GET["delete_category"])) {
//     $query = "DELETE FROM category WHERE id= ?";
//     $stmt = $conn->prepare($query);
//     if ($stmt) {
//         $stmt->bind_param('i', $_POST['id']);
//         $stmt->execute();
//         echo "Record Deleted :";
//         echo $stmt->affected_rows;
//     } else {
//         echo $conn->error;
//     }
// }

/////add product

if (isset($_POST['add_product'])) {
    $cat_id = $_POST['cat_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $small_desc = $_POST['small_desc'];
    $desc = $_POST['desc'];
    $o_price = $_POST['o_price'];
    $s_price = $_POST['s_price'];
    $qty = $_POST['qty'];
    $status = isset($_POST['status']) ? '1' : '0';
    $trending = isset($_POST['trending']) ? '1' : '0';
    $metaTitle = $_POST['metatitle'];
    $metaDesc = $_POST['metadesc'];
    $metaKey = $_POST['metakey'];

    $img = $_FILES['img']['name'];
    $path = "uploads";
    $image_ext = pathinfo($img, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    $query = "INSERT INTO products (category_id,name,slug,small_desc,description,original_price,selling_price,image,quantity,status,trending,meta_title,meta_keyword,meta_desc) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssssssssssss", $cat_id, $name, $slug, $small_desc, $desc, $o_price, $s_price, $filename, $qty, $status, $trending, $metaTitle, $metaKey, $metaDesc);
        if ($stmt->execute()) {
            // echo "No of records inserted : " . $conn->affected_rows;
            // header("location:login.php");
            // var_dump('adada');
            move_uploaded_file($_FILES['img']['tmp_name'], $path . '/' . $filename);
            header('location: viewProducts.php');
        } else {
            echo $conn->error;
        }
    } else {
        echo $conn->error;
    }
}
/////// edit Product

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $cat_id = $_POST['cat_id'];
    // var_dump($id);
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $small_desc = $_POST['small_desc'];
    $desc = $_POST['desc'];
    $o_price = $_POST['o_price'];
    $s_price = $_POST['s_price'];
    $qty = $_POST['qty'];
    $status = isset($_POST['status']) ? '1' : '0';
    $trending = isset($_POST['trending']) ? '1' : '0';
    $metaTitle = $_POST['metatitle'];
    $metaDesc = $_POST['metadesc'];
    $metaKey = $_POST['metakey'];

    $img = $_FILES['img']['name'];
    $old_img = $_POST['oldimg'];
    if ($img != "") {
        // $update_filename = $img;
        $image_ext = pathinfo($img, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $img;
    } else {
        $update_filename = $old_img;
    }
    $path = "uploads";

    $query = "UPDATE products SET category_id = ?,name = ? ,slug = ?, small_desc = ?,description = ?,original_price = ?,selling_price = ? ,quantity = ?,status =? ,trending = ?,meta_title = ? ,meta_desc = ? ,meta_keyword = ?,image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssssssssssssi", $cat_id, $name, $slug, $small_desc, $desc, $o_price, $s_price, $qty, $status, $trending, $metaTitle, $metaDesc, $metaKey,  $update_filename, $id);
        if ($stmt->execute()) {
            // echo "No of records inserted : " . $conn->affected_rows;
            // header("location:login.php");
            // var_dump('adada');
            if ($img = $_FILES['img']['name'] != "") {

                move_uploaded_file($_FILES['img']['tmp_name'], $path . '/' . $update_filename);
                if (file_exists("uploads/" . $old_img)) {
                    unlink("uploads/" . $old_img);
                }
            }
            // header("location:editCategory.php?id=$id");
            header("location:viewProducts.php");
        } else {
            echo $conn->error;
        }
    } else {
        echo $conn->error;
    }
}

/////// Delete product
$con = mysqli_connect("localhost", "root", "himalayan", "project");
if (isset($_POST['delete_product'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $select_query = "SELECT * FROM products WHERE id = '$product_id'"; //Deleting image
    $select_query_run = mysqli_query($con, $select_query);
    $products_data = mysqli_fetch_array($select_query_run);
    $image = $products_data['image'];
    $delete_query = "DELETE FROM products WHERE id='$product_id'"; // Deleting data
    $delete_query_run = mysqli_query($con, $delete_query);
    if ($delete_query_run) {
        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }
        header("location:viewProducts.php");
    } else {
        // header("location:viewProducts.php");
        echo "Something went Wrong";
    }
}
