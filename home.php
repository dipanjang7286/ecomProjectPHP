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
include_once 'part/nav.php';

?>


<div class="container">
    <h2 align="center">Home Page</h2>

    <h2 align="center">Welcome User Home</h2>

</div>

<?php include_once 'part/footer.php';?>
