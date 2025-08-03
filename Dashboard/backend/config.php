<?php 
header("Access-Control-Allow-Origin: *"); // فقط برای تست، بعداً محدود کن به دامنه React
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json");

//object تعریف شده چون از تابع های prepare , bind_param استفاده شده
$conn = new mysqli("localhost","root","","see5-dashboard");
// $conn = mysqli_connect("localhost","root","","see5-dashboard");


session_start();
