// src/App.tsx
import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import UserPanel from "./pages/UserPanel";
import AdminPanel from "./AdminPanel";
import Profile from "./pages/profile";

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/admin" element={<AdminPanel />} />
        <Route path="/user" element={<UserPanel />} />

        <Route path="/profile" element={<Profile />} />
        {/* نکته: اسم route ها و تابع هایی که export default میشن، حتما باید با حرف بزرگ نمایش داده بشه */}
        {/* وگرنه ری اکت ارور میده */}
        {/* JSX عناصر با حرف کوچیک رو به عنوان تگ HTML در نظر می‌گیره */}
      </Routes>
    </BrowserRouter>
  );
}
