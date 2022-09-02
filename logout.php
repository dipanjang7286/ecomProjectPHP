<?php

setcookie('uname', "", time() - 3600);
header('location:login.php');
die();
