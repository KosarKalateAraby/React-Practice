<?php 

include('config.php');


//داده‌هایی که از طریق درخواست HTTP با فرمت JSON ارسال شدن رو می‌خونه، تجزیه (parse) می‌کنه و به صورت یک آرایه‌ی PHP ذخیره می‌کنه در متغیر $data

//json_decode = true --> آرایه انجمنی
//json_decode = false --> object

//php://input میاد بدنه خام و دیتاهای با فرمت json رو json رو میگیره

//get_the_content() میاد محتوای آدرس رو میگیره و به صورت رشته برمیگردونه
$data = json_decode(file_get_contents("php://input"), true);



// کلید name رو از data بگیر اگر وجود داشت
// اگر وجود نداشت خالی بزار ''
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($email) || empty($password) || empty($name)){
    echo json_encode([ //json_encode مقدار های php رو تبدیل به json میکنه و میفرسته به جاوااسکریپت
        'status' => 'error',
        'message' => 'لطفا همه فیلدهارا پر کنید'
    ]);
    exit;
}

// بررسی تکراری نبودن ایمیل

//اماده کردن query و گرفتن id کاربر از هرجا که email مساوی با جایی بود که بعدا پرش میکنیم
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");

// جای اون علامت سوال، متغیر ایمیل رو با تایپ string قرار بده
$stmt->bind_param("s", $email);

// اجرای دستور SQL و فرستادن به دیتابیس
$stmt->execute();

//ذخیره نتیجه query در متغیر stmt تا بررسیش کنیم
$stmt->store_result();

// اگر تعداد نتایج از دیتابیس بیشتر از 0 بود یعنی ایمیل قبلا وجود داشت، ارور بده و کد اجرا نشه
if ($stmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'این ایمیل قبلا ثبت شده است']);
    exit;
}

//کوئری دیگه اجرا نمیشه و بیشتر برای ساختار تمیز کدهاست
$stmt->close();

//هش کردن یعنی تبدیل یک مقدار به یک رشته‌ی غیرقابل برگشت
//برای امنیت رمز عبور و جلوگیری از هک
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn -> prepare("INSERT INTO users (name , email , password) VALUES (? , ? , ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['user'] = [
        'email' => $email,
        'role' => 'user'
    ];

    echo json_encode([
        'status' => 'success', 
        'message' => 'ثبت‌نام با موفقیت انجام شد']);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'خطا در ثبت‌نام']);
}

//ازاد کردن منابع و بالا بردن سرعت سایت و جلوگیری از کرش کردن
$stmt->close();
$conn->close();