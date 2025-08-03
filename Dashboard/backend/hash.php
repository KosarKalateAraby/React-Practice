<?php
$password = 'admin123'; // پسورد ادمین که می‌خوای وارد دیتابیس بشه
$hashed = password_hash($password, PASSWORD_DEFAULT);

echo $hashed;
