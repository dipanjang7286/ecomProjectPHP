<?php
require 'vendor/autoload.php';
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if (!isset($_COOKIE['uname']) || !isset($_COOKIE['jwt'])) {
    header('location:login.php');
}
$key = 'example_key';
$decoded = JWT::decode($_COOKIE['jwt'], new Key($key, 'HS256'));
$decoded_array = (array) $decoded;
var_dump($decoded_array);
var_dump ($decoded_array['data']->user_type);
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



if ($_GET['redirect_status']== 'succeeded') {
    // $fname = $_POST['fname'];
    // $lname = $_POST['lname'];
    // $contactNO = $_POST['cno'];
    // $email = $_POST['email'];
    // $pin = $_POST['pin'];
    // $address = $_POST['address'];
    $id = $decoded_array['data']->id;
    $fname = $decoded_array['data']->fname;
    $lname = $decoded_array['data']->lname;
    $contactNO = 'cno';
    $email = $decoded_array['data']->uname;
    $pin = 'pin';
    $address = 'address';
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
    $tracking_id = "null";
    $payment_mode = "CARD";
    $payment_id = $_GET['payment_intent'];
    $status = isset($_POST['status']) ? '1' : '0';
    $comments = "null";
    // var_dump($totalPrice);
    $query = "INSERT INTO orders (tracking_id,user_id,fname,lname,email,phone,address,pincode,total_price,payment_mode,payment_id,status,comments) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sssssssssssss", $tracking_id, $id, $fname, $lname, $email, $contactNO, $address, $pin, $totalPrice, $payment_mode, $payment_id, $status, $comments);
        if ($stmt->execute()) {
            $last_inserted_id = $conn->insert_id;
            foreach ($rows as $r) {
                $query2 = "INSERT INTO orders_items (order_id,product_id,quantity,price) values(?,?,?,?)";
                $stmt1 = $conn->prepare($query2);
                if ($stmt1) {
                    $stmt1->bind_param("isss", $last_inserted_id, $r['product_id'], $r['quantity'], $r['selling_price']);
                    if ($stmt1->execute()) {
                        ////update products
                    } else {
                    }
                } else {
                    echo $conn->error;
                }
            }
            $con = mysqli_connect("localhost", "root", "himalayan", "project");
            $deleteQuery = "DELETE FROM cart WHERE user_id=$id";
            $deleteQuery_run = mysqli_query($con, $deleteQuery);
            // // echo "No of records inserted : " . $conn->affected_rows;
            // // header("location:login.php");
            // // var_dump('adada');
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            
            $eid = $decoded_array['data']->uname;
            // var_dump($eid);
            $name = $decoded_array['data']->fname .' '. $decoded_array['data']->lname;
            $body ='Hello Mr.'.' '. $name .','.'<br>' . 'We would like to inform you that, Your order is confirmed.'.'<br>'.'For more details please login in our site.'.'<br>'.'Thank You.';
            try{
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "tls";
                $mail->Port = "587";
                $mail->Username = "testprojects701@gmail.com";
                $mail->Password = "zcgcwitffsiuowdw";
                $mail->setFrom('testprojects701@gmail.com','Order Confirm');
                $mail->addAddress($eid);
                
                $mail->Subject = 'Confirm Order';
                $mail->Body = $body;
                // $mail->AltBody = 'Your Order is confirmed. For more details please login and go to my order section.';
                $mail->isHTML(true);
                $mail->send();
                echo "Email send Successfully";
            }catch(Exception $e){
                echo "Could not Send Email", $mail->ErrorInfo;
            }
            $mail->smtpClose();
            header('location: myOrders.php');
        } else {
            echo $conn->error;
        }
    } else {
        echo $conn->error;
    }
}

?>