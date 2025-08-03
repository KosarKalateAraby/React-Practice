// src/pages/Login.tsx
import React, { useState } from 'react';
import FormInput from '../components/FormInput';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleLogin = async() => {

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("ایمیل نامعتبر است");
        return;
    }

    if (password.length < 8) {
        alert("رمز عبور باید حداقل ۸ کاراکتر باشد");
        return;
    }

    // if (email && password) {
    //     if (email === "admin@gmail.com" && password === "12345678") {
    //     navigate("/admin"); // ورود به پنل ادمین
    //     } else {
    //     navigate("/user"); // ورود به پنل کاربری معمولی
    //     }
    // }

    try{
      const response = await axios.post('http://localhost/See5/Dashboard/backend/login.php', {
        email,
        password,
      });

      if (response.data.status === 'success'){
        if (response.data.role === 'admin'){
          navigate('/admin')
        } else{
          navigate('/user')
        }
      } else{
        alert('ورود ناموفق بود:' + response.data.message);
      }
    }
    catch(error : any){
      console.error(error);
      alert('خطا در ارتباط با سرور');
    }
  };


  return (
    <div className="flex items-center justify-center min-h-screen bg-gray-100">
      <div className="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
        <h2 className="text-2xl font-bold mb-6 text-center">ورود</h2>
        <FormInput label="ایمیل" value={email} onChange={(e) => setEmail(e.target.value)} />
        <FormInput label="رمز عبور" type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
        <button onClick={handleLogin} className="w-full bg-blue-500 text-white py-2 rounded-lg mt-4 hover:bg-blue-600">
          ورود
        </button>
      </div>
    </div>
  );
}
