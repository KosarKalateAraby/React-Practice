// src/pages/Register.tsx
import React, { useState } from "react";
import FormInput from "../components/FormInput";
import { useNavigate } from "react-router-dom";
import axios from "axios";

export default function Register() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [name, setName] = useState("");
  const navigate = useNavigate();
  const LoginLink = () => {
    navigate("/login");
  };

  const handleRegister = async () => {
    if (!name || !email || !password) {
      alert("لطفا همه فیلدها را پر کنید");
      return;
    }
    try {
      const response = await axios.post(
        "http://localhost/See5/React-Practice/Dashboard/backend/register.php",
        {
          email,
          password,
          name,
        }
      );

      if (response.data.status === "success") {
        localStorage.setItem("token", response.data.token);
        alert("ثبت نام موفق بود");
        navigate("/user");
      } else {
        alert("ثبت نام ناموفق بود");
      }
    } catch (error: any) {
      console.error(error);
      alert("خطا در ارتباط با سرور");
    }
  };

  return (
    <div className="flex items-center justify-center min-h-screen bg-gray-100">
      <div className="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
        <h2 className="text-2xl font-bold mb-6 text-center">ثبت‌نام</h2>
        <FormInput
          label="نام"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        <FormInput
          label="ایمیل"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <FormInput
          label="رمز عبور"
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <button
          onClick={handleRegister}
          className="w-full bg-green-500 text-white py-2 rounded-lg mt-4 hover:bg-green-600"
        >
          ثبت‌نام
        </button>
        <p
          className="text-[12px] text-center mt-7 text-blue-600 hover:underline cursor-pointer"
          onClick={LoginLink}
        >
          قبلا ثبت نام کردید؟ کلیک کنید
        </p>
      </div>
    </div>
  );
}
