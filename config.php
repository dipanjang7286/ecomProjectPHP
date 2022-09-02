<?php
use Stripe\Stripe;
require_once "vendor/stripe/stripe-php/init.php";
require_once('vendor/autoload.php');
$pk = "pk_test_51LUr1fSDn8RQwPVIuCFLqDC7TJGc2400lxBMeLl6npsNnH5cXW2rAXrQaGVQtFs4oUPDdAoGuN0OhIpz4xxSagkO00a93QtAPd";
$sk = "sk_test_51LUr1fSDn8RQwPVISV2i8pj2iEeaT5lgpL6T4Igd20TrcHlyEPRO6RGmkdD6nTM9T0nkHzvJyT8NxvHqmBPSVdFd00gRoUAITH";
\Stripe\Stripe::setApiKey($sk);
?>