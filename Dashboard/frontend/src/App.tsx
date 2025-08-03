// src/App.tsx
import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Login from './pages/Login';
import Register from './pages/Register';
import UserPanel from './pages/UserPanel';

function AdminPanel() {
  return <div className="bg-slate-100 rounded-xl shadow-xl w-full min-h-max mx-auto">
      <p className='text-center'>ادمین گرامی! خوش آمدید</p>
    </div>;
}

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/admin" element={<AdminPanel />} />
        <Route path="/user" element={<UserPanel />} />
      </Routes>
    </BrowserRouter>
  );
}
