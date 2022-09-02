<?php include_once 'part/header.php' ?>
<?php include_once 'part/nav.php' ?>

<div class="container">
    <h1 class="text-center">Register here</h1>
    <form action="api.php" method="post">
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" id="fname" name="fname" aria-describedby="" placeholder="Enter First Name">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="lname">First Name</label>
            <input type="text" class="form-control" id="lname" name="lname" aria-describedby="" placeholder="Enter Last Name">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="uname">Email address</label>
            <input type="email" class="form-control" id="uname" name="uname" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                else.</small>
        </div>
        <div class="form-group">
            <label for="pass">Password</label>
            <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
            <small id="emailHelp" class="form-text text-muted">Enter password</small>
        </div>
        <div class="form-group">
            <label for="cpass">Confirm Password</label>
            <input type="password" class="form-control" id="cpass" name="cpass" placeholder="Password">
            <small id="emailHelp" class="form-text text-muted">Confirm password</small>
        </div>
        <button type="submit" class="btn btn-primary" id="reg" name="reg" value="1">Register</button>
    </form>
</div>
<?php include_once 'part/footer.php' ?>
