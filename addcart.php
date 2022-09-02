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
include_once 'db.php';
$conn = $GLOBALS["connection"];
if(isset($_POST['scope'])){
    $scope = $_POST['scope'];
    switch($scope){
        case "add" :
            $product_id=$_POST['product_id'];
            $product_qty = $_POST['product_qty'];
            $user_id = $decoded_array['data']->id;
            // var_dump($user_id);
            $query = "INSERT INTO cart (user_id,product_id,quantity) values(?,?,?)";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("sss", $user_id, $product_id, $product_qty);
                if ($stmt->execute()) {
                    // echo "No of records inserted : " . $conn->affected_rows;
                    // header("location:login.php");
                } else {
                    echo $conn->error;
                }
            } else {
                echo $conn->error;
            }
            break;
        case "update":
            // $con = mysqli_connect("localhost", "root", "himalayan", "project");
            $product_id = $_POST['product_id'];
            $product_qty = $_POST['product_qty'];
            $user_id = $decoded_array['data']->id;
            $query = "UPDATE cart SET quantity = ? WHERE user_id =? AND product_id =?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("sss",$product_qty, $user_id, $product_id);
                if ($stmt->execute()) {
                    // echo "No of records inserted : " . $conn->affected_rows;
                    // header("location:login.php");
                } else {
                    echo $conn->error;
                }
            } else {
                echo $conn->error;
            }
            break;
        case "remove":
            $con = mysqli_connect("localhost", "root", "himalayan", "project");
            $cart_id = $_POST['cart_id'];
            $user_id = $decoded_array['data']->id;
            // var_dump($user_id);
            $cart_check = "SELECT * FROM cart WHERE id ='$cart_id' and user_id='$user_id'";
            $cart_check_run=mysqli_query($con,$cart_check);
            if(mysqli_num_rows($cart_check_run)>0){
                $delete="DELETE FROM cart WHERE id='$cart_id'";
                $delete_run=mysqli_query($con,$delete);
            }else{
                echo "Somthing Went Wrong";
            }
            
        default:
            echo "500";

    }

}else{
    echo "scope is not set";
}
?>

<?php include_once 'part/footer.php'; ?>
