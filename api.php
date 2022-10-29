<?php
include_once 'db.php';
require "vendor/autoload.php";

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
$conn = $GLOBALS["connection"];

if (isset($_POST['reg'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $uname = $_POST["uname"];
    $pass = $_POST["pass"];
    $hassedPass = password_hash($pass, PASSWORD_DEFAULT);
    $cpass = $_POST["cpass"];
    if ($pass == $cpass) {
        $query = "INSERT INTO users (fname,lname,uname,hpass) values(?,?,?,?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssss", $fname, $lname, $uname, $hassedPass);
            if ($stmt->execute()) {
               
                header("location:login.php");
            } else {
                
                echo $conn->error;
            }
        } else {
            echo $conn->error;
        }
    }
}

//
if (isset($_COOKIE['uname'])) {
    header('location:index.php');
}
// session_start();
if (isset($_POST['login'])) {
    $uname = $_POST["uname"];
    $pass = $_POST['pass'];
    $query = "SELECT * FROM users WHERE uname='$uname' LIMIT 0,1";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        if ($stmt->execute()) {
            // echo "No of records inserted : " . $conn->affected_rows;
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            var_dump($row);
            if (mysqli_num_rows($result) > 0) {
                if (password_verify($pass, $row['hpass'])) {
                    setcookie('uname', $row['uname'], time() + 3600);
                    $key = 'example_key';
                    $token = [
                        'iss' => 'http://example.org',
                        'aud' => 'http://example.com',
                        'iat' => 1356999524,
                        'nbf' => 1357000000,
                        "data" => array(
                                    "id" => $row['id'],
                                    "fname" => $row['fname'],
                                    "lname" => $row['lname'],
                                    "uname" => $row['uname'],
                                    "user_type" => $row['user_type']
                                )
                    ];
                    $jwt = JWT::encode($token, $key, 'HS256');
                    setcookie("jwt", $jwt);
                    //user type check
                    if ($token['data']['user_type'] == 'admin') {
                        header("location:welcome.php");
                    } elseif ($token['data']['user_type'] == 'user') {
                        header('location:categories.php');
                    } else {
                        header('location:login.php');
                    }
                    
                }else{
                    header("location:login.php");
                }    
                
            }
        } else {
        }
    } else {
    }
}


/////// edit user

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    // var_dump($cat_id);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $uname = $_POST['uname'];
    $userType = $_POST['user_type'];
    $query = "UPDATE users SET fname = ?,lname = ? ,uname = ?, user_type = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssi", $fname, $lname, $uname, $userType, $id);
        if ($stmt->execute()) {
            // echo "No of records inserted : " . $conn->affected_rows;
            // header("location:login.php")
            header("location:welcome.php");
        } else {
            echo $conn->error;
        }
    } else {
        echo $conn->error;
    }
}

////// delete User
$con = mysqli_connect("localhost", "root", "", "project");
if (isset($_POST['delete_user'])) {
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $delete_query = "DELETE FROM users WHERE id='$user_id'"; // Deleting data
    $delete_query_run = mysqli_query($con, $delete_query);
    if ($delete_query_run) {
        header("location:welcome.php");
    } else {
        // header("location:viewProducts.php");
        echo "Something went Wrong";
    }
}
