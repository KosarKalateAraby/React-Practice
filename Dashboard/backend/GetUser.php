<?php

//require: این دستور فایل مورد نظر رو لود و اجرا می‌کنه. اگه فایل پیدا نشه، اجرای کل برنامه متوقف میشه و خطا میده
//__DIR__: یک ثابت داخلی که مسیر کامل پوشه‌ی فعلی همین فایل رو برمی‌گردونه
require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
//کلاس JWT رو از کتابحونه مربوطش بیار تا مستقیم بتونم استفادش کنم. 
// مثل import کردن در react

include('config.php');

// هندل کردن preflight request
// option یک درخواست فقط برای بررسی هستش و درخواست واقعی نیست
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {  //آیا متد درخواست فعلی OPTIONS هست یا نه 
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Credentials: true"); //مجوز ارسال کوکی همراه با درخواست
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Content-Type: application/json");
    http_response_code(200); //پاسخ ساده با کد 200 یعنی اوکیه
    exit;
}

// تنظیمات هدر
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json");


// دریافت توکن از هدر Authorization

$headers = apache_request_headers(); //تابع همه هدرهای HTTP که همراه درخواست به سرور اومدن رو برمی‌گردونه
$authHeader = $headers['Authorization'] ?? ''; //بررسی وجود Authorization داخل آرایه headers


if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) { //بررسی شروع رشته authHeader با کلمه Bearer
    http_response_code(401); 
    echo json_encode(['error' => 'توکن ارسال نشده یا نامعتبر است']);
    exit;
}

$jwt = str_replace('Bearer ', '', $authHeader); // حذف کلمه Bearer از متغیر authHeader

try{
    $secret_key = 'T9vL6wPzYx4N1qKsRf8JuE2MhB0cZaXdTg3Br7VoWmUe5CyHkQiLnApZsEjGtX9b';
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    //JWT::decode() یکی از توابع کتابخانه Firebase JWT
    //با استفاده از متغیر secret_key متغیر jwt رو باز کن و ببین الگوریتمش HS256 هستش یا نه.

    $email = $decoded->email ?? null; //از آرایه decode مقدار email رو میگیره و ذخیره میکنه

    if (!$email) {
        throw new Exception('Invalid token: missing email'); //خطا نشون میده ایمیل وجود نداره
    }

    $stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'User not found']);
        http_response_code(404);
    }

    // بستن منابع
    $stmt->close();
    $conn->close();
}
catch (Exception $e){ //یعنی هر استثنایی که در بلوک اتفاق افتاد، بدون توجه به نوعش، اینجا بگیرش
    http_response_code(401);
    echo json_encode(['error' => 'توکن نامعتبر است', 'details' => $e->getMessage()]);
}

?>

