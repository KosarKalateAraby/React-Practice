<?php 

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET , OPTIONS");
header("Content-Type: application/json");

//object تعریف شده چون از تابع های prepare , bind_param استفاده شده
$conn = new mysqli("localhost","root","","see5-dashboard");
// $conn = mysqli_connect("localhost","root","","see5-dashboard");


?>