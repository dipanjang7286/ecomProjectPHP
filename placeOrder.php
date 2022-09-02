<?php
require "vendor/autoload.php";

use Stripe\Stripe;
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
include_once 'db.php';
$conn = $GLOBALS["connection"];
$id = $decoded_array['data']->id;
$query1 = "SELECT c.id AS cid ,c.product_id,c.quantity,p.name,p.selling_price,p.image  FROM cart c ,products p WHERE c.product_id=p.id AND c.user_id='$id' ORDER BY c.id DESC";
$stmt = $conn->prepare($query1);
if ($stmt->execute()) {

    $result1 = $stmt->get_result();
    $rows = $result1->fetch_all(MYSQLI_ASSOC);
    // var_dump($rows);
}
$totalPrice = 0;
foreach ($rows as $r) {
    $totalPrice += $r['selling_price'] * $r['quantity'];
}

///////////////////////
// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51LUr1fSDn8RQwPVISV2i8pj2iEeaT5lgpL6T4Igd20TrcHlyEPRO6RGmkdD6nTM9T0nkHzvJyT8NxvHqmBPSVdFd00gRoUAITH');
// $items = $totalPrice *100;
function calculateOrderAmount(array $items): int {
    // Replace this constant with a calculation of the order's amount
    // Calculate the order total on the server to prevent
    // people from directly manipulating the amount on the client
    return 100000;
}

header('Content-Type: application/json');

try {
    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    // Create a PaymentIntent with amount and currency
    $paymentIntent = \Stripe\PaymentIntent::create([
        // 'amount' => calculateOrderAmount($jsonObj->items),
        'amount' => $totalPrice*100,
        'currency' => 'inr',
        'automatic_payment_methods' => [
            'enabled' => true,
        ],
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
    
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
//////////////////////
//// add Order
// if ($_GET['redirect_status']== 'succeeded') { 
//     $fname = $_POST['fname'];
//     $lname = $_POST['lname'];
//     $contactNO = $_POST['cno'];
//     $email = $_POST['email'];
//     $pin = $_POST['pin'];
//     $address = $_POST['address'];
//     $id = $decoded_array['data']->id;
//     $query1 = "SELECT c.id AS cid ,c.product_id,c.quantity,p.name,p.selling_price,p.image  FROM cart c ,products p WHERE c.product_id=p.id AND c.user_id='$id' ORDER BY c.id DESC";
//     $stmt = $conn->prepare($query1);
//     if ($stmt->execute()) {

//         $result1 = $stmt->get_result();
//         $rows = $result1->fetch_all(MYSQLI_ASSOC);
//         // var_dump($rows);
//     }
//     $totalPrice = 0;
//     foreach ($rows as $r) {
//         $totalPrice += $r['selling_price'] * $r['quantity'];
//     }
//     $tracking_id = "null";
//     $payment_mode = "COD";
//     $payment_id = "null";
//     $status = isset($_POST['status']) ? '1' : '0';
//     $comments = "null";
//     // var_dump($totalPrice);
//     $query = "INSERT INTO orders (tracking_id,user_id,fname,lname,email,phone,address,pincode,total_price,payment_mode,payment_id,status,comments) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
//     $stmt = $conn->prepare($query);
//     if ($stmt) {
//         $stmt->bind_param("sssssssssssss", $tracking_id, $id, $fname, $lname, $email, $contactNO, $address, $pin, $totalPrice, $payment_mode, $payment_id, $status, $comments);
//         if ($stmt->execute()) {
//             $last_inserted_id = $conn->insert_id;
//             foreach ($rows as $r) {
//                 $query2 = "INSERT INTO orders_items (order_id,product_id,quantity,price) values(?,?,?,?)";
//                 $stmt1 = $conn->prepare($query2);
//                 if ($stmt1) {
//                     $stmt1->bind_param("isss", $last_inserted_id, $r['product_id'], $r['quantity'], $r['selling_price']);
//                     if ($stmt1->execute()) {
//                         ////update products
//                     } else {
//                     }
//                 } else {
//                     echo $conn->error;
//                 }
//             }
//             $con = mysqli_connect("localhost", "root", "himalayan", "project");
//             $deleteQuery = "DELETE FROM cart WHERE user_id=$id";
//             $deleteQuery_run = mysqli_query($con, $deleteQuery);
//             // // echo "No of records inserted : " . $conn->affected_rows;
//             // // header("location:login.php");
//             // // var_dump('adada');
//             header('location: myOrders.php');
//         } else {
//             echo $conn->error;
//         }
//     } else {
//         echo $conn->error;
//     }
// }
