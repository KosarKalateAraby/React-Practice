import React, { useEffect, useState } from "react";
import axios from "axios";
import UserPanel from "./UserPanel";

export default function Profile() {
  const [userData, setUserData] = useState<{
    name: string;
    email: string;
  } | null>(null);
  const [error, setError] = useState<string | null>(null);

  //وقتی اجرا میشه که کامپوننت لود بشه
  useEffect(() => {
    const token = localStorage.getItem("token"); // گرفتن توکن از localStorage

    axios
      .get(
        "http://localhost/See5/React-Practice/Dashboard/backend/GetUser.php",
        {
          headers: {
            Authorization: `Bearer ${token}`, // ارسال توکن در هدر
          },
        }
      )
      .then((res) => {
        setUserData(res.data);
      })
      .catch((err) => {
        setError(err.response?.data?.error || err.message);
      });
  }, []);

  return (
    <div className="flex items-center justify-center bg-gray-100 min-h-screen">
      <UserPanel/>
      <div className="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
        <h2 className="text-2xl font-bold mb-6 text-center">پروفایل</h2>

        {/*  واگر خطا وجود داشت نمایش داده میشه واگرنه مقدارش null میشه */}
        {error && <p className="text-red-500">{error}</p>}

        {userData ? (
          <div className="space-y-4">
            <div>
              <p className="font-semibold">نام:</p>
              <p>{userData.name}</p>
            </div>
            <div>
              <p className="font-semibold">ایمیل:</p>
              <p>{userData.email}</p>
            </div>
          </div>
        ) : (
          !error && <p>در حال بارگذاری اطلاعات...</p>
        )}
      </div>
    </div>
  );
}
