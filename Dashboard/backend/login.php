<?php 

require __DIR__ . '/vendor/autoload.php'; // اتصال به autoload کامپوزر

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include('config.php');

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET , OPTIONS");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($email) || empty($password)){
    echo json_encode([
        'status' => 'error',
        'message' => 'ایمیل یا رمز عبور ارسال نشده'
    ]);
    exit;
}

// اماده کردن query و گرفتن id کاربر از هرجا که email مساوی با جایی بود که بعدا پرش میکنیم
$stmt = $conn->prepare("SELECT id , password , role FROM users WHERE email = ?");

// جای اون علامت سوال، متغیر ایمیل رو با تایپ string قرار بده
$stmt->bind_param("s", $email);

// اجرای دستور SQL و فرستادن به دیتابیس
$stmt->execute();

//ذخیره نتیجه query در متغیر stmt تا بررسیش کنیم
$result = $stmt->get_result();

if ($result -> num_rows === 1){ //num_rows تعداد ردیف هایی که query برگردونده رو نمایش میده
    $user = $result->fetch_assoc(); //fetch_assoc میاد از روی نتیجه query یک آرایه انجمنی میسازه

    // بررسی رمز عبور
    if (password_verify($password, $user['password'])) { 
        //password_verify یک بررسی رمز عبور امن هستش که پارامتر اول رمز خام کاربر هستش
        // و پارامتر دوم رمز هش شده

        // ✅ اگر رمز صحیح بود، حالا JWT تولید کنیم
        $secret_key = 'T9vL6wPzYx4N1qKsRf8JuE2MhB0cZaXdTg3Br7VoWmUe5CyHkQiLnApZsEjGtX9b';

        $payload = [
            "id" => $user['id'],
            "email" => $email,
            "role" => $user['role'],
            // "exp" => time() + 3600 // ⏰ انقضا: 1 ساعت
        ];

        // ساخت توکن
        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        file_put_contents("debug_token.txt", $jwt); // ذخیره توکن در فایل

        // ارسال توکن به فرانت
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'role' => $user['role'],
            'token' => $jwt
        ]);
        exit;
    } 
    else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'رمز عبور اشتباه است']);
}
}
else {
    echo json_encode([
        'status' => 'error',
        'message' => 'کاربری با این ایمیل پیدا نشد'
    ]);
}


//ازاد کردن منابع و بالا بردن سرعت سایت و جلوگیری از کرش کردن
$stmt->close();
$conn->close();