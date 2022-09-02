<?php
// require "vendor/autoload.php";

// use \Firebase\JWT\JWT;
// use Firebase\JWT\Key;

// if (!isset($_COOKIE['uname']) || !isset($_COOKIE['jwt'])) {
//     // header('location:login.php');
// }
// $key = 'example_key';
// $decoded = JWT::decode($_COOKIE['jwt'], new Key($key, 'HS256'));
// $decoded_array = (array) $decoded;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!-- <a class="navbar-brand" href="categories.php">Categories</a> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php if (isset($_COOKIE['uname'])) { ?>

                <li class="nav-item active"><a class="nav-link" href="categories.php">Home <span class="sr-only"></span></a></li>
                <li class="nav-item active float-right"><a class="nav-link" href="viewcart.php">Cart <span class="sr-only"></span></a></li>
                <li class="nav-item active float-right"><a class="nav-link" href="myOrders.php">My Orders <span class="sr-only"></span></a></li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php } ?>
            <?php if (isset($_COOKIE['uname'])) { ?>

                <li class="nav-item"><a class="nav-link" href="logout.php">LogOut</a></li>

            <?php } ?>


        </ul>
    </div>
</nav>
<?php include_once 'part/footer.php' ?>