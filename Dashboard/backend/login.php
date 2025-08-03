<?php 

include('config.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET");
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

//فرضا کاربر هستن. بعدا وارد دیتابیس میشه
// $users = [
//     'admin@gmail.com' => ['password' => '12345678', 'role' => 'admin'],
//     'user@gmail.com' => ['password' => '87654321', 'role' => 'user'],
// ];

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

        $_SESSION['user'] = [
            'email' => $email,
            'role' => $user['role'],
        ];
        echo json_encode([
        'status' => 'success', 
        'message' => 'ثبت‌نام با موفقیت انجام شد',
        'role' => $user['role']]);
    } 
    else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'خطا در ورود']);
}
}

//ازاد کردن منابع و بالا بردن سرعت سایت و جلوگیری از کرش کردن
$stmt->close();
$conn->close();