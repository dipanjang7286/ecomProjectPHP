<?php include_once 'part/header.php' ?>
<?php include_once 'part/nav.php' ?>
<?php

?>
<div class="container">
    <h1 class="text-center">Login here</h1>
    <form action="api.php" method="post">
        <div class="form-group">
            <label for="uname">Email address</label>
            <input type="email" class="form-control" id="uname" name="uname" aria-describedby="emailHelp" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="pass">Password</label>
            <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
        </div>
        <input type="hidden" id="id" name="id" >

        <button type="submit" class="btn btn-primary" id="login" name="login" value="1">Login</button>
    </form>
</div>
<?php include_once 'part/footer.php' ?>