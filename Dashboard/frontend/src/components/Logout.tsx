import React from "react";
import { FaSignOutAlt } from "react-icons/fa";
import { NavLink, useNavigate } from "react-router-dom";

export default function LogoutButton() {

    const navigate = useNavigate();
    const handleLogout = (e: React.MouseEvent) => {
    e.preventDefault();
    const confirmForm = window.confirm("مطمئنید میخواین خروج کنید؟");

    if (confirmForm) {
      localStorage.removeItem("token");
      navigate("/");
    }
  };
  const getActiveClass = ({ isActive }: { isActive: boolean }) =>
    `flex items-center gap-4 p-2 rounded-lg transition-colors ${
      isActive ? "bg-blue-500 text-white" : "hover:bg-blue-500 hover:text-white"
    }`;

  return (
    <NavLink to="/"
    onClick={handleLogout}
    className={getActiveClass}
    >
        <FaSignOutAlt />
        خروج
    </NavLink>
  );
}
