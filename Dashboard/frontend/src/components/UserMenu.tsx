// src/pages/UserPanel.tsx
import React from "react";
import {
  FaUser,
  FaShoppingCart,
  FaSignOutAlt,
  FaTimes,
  FaBars,
} from "react-icons/fa";
import { useState } from "react";
import { NavLink } from "react-router-dom";

export default function UserMenu() {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const getActiveClass = ({ isActive }: { isActive: boolean }) =>
    `flex items-center gap-4 p-2 rounded-lg transition-colors ${
      isActive ? "bg-blue-500 text-white" : "hover:bg-blue-500 hover:text-white"
    }`;

  return (
    <div className="flex min-h-screen bg-gray-100 relative">
      <button
        className="md:hidden fixed top-4 right-4 z-50 text-2xl text-gray-500"
        onClick={() => setSidebarOpen(!sidebarOpen)}
      >
        {sidebarOpen ? <FaTimes /> : <FaBars />}
      </button>

      <aside
        id="sidebar"
        className={`fixed top-0 right-0 h-full w-44 lg:w-48 bg-white p-4 shadow-lg transform transition-transform duration-300 z-40
        ${sidebarOpen ? "translate-x-0" : "translate-x-full"} md:translate-x-0`}
      >
        <h2 className="text-lg lg:text-xl font-bold mb-4 text-center">پنل کاربری</h2>
        <div className="space-y-3">
          <div className="text-sm">
            <NavLink
              to="/profile" //آدرس مرورگر برابر با /profle باشه، NavLink به طور خودکار لینک رو active در نظر می‌گیره
              className={getActiveClass} //isActive توسط کامپوننت NavLink به صورت خودکار در اختیار تابع className قرار می‌گیره
            >
              <FaUser />
              پروفایل من
            </NavLink>
            <NavLink to="/cart" className={getActiveClass}>
              <FaShoppingCart />
              سبد خرید
            </NavLink>
            <NavLink to="/" className={getActiveClass}>
              <FaSignOutAlt />
              خروج
            </NavLink>
          </div>
        </div>
      </aside>
    </div>
  );
}
