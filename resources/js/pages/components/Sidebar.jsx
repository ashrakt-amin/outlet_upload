import { AiFillHome } from "react-icons/ai";
import { NavLink } from "react-router-dom";
import { useContext } from "react";
import { darkTheme } from "../context/darkTheme";
export default function Sidebar() {
    const { toggleMenu, isShow, isDark } = useContext(darkTheme);
    return (
        <div
            className={
                isShow
                    ? `min-h-screen min-w-full flex justify-center md:min-w-[250px] fixed left-0 pt-16 z-40 transition-all ease-in-out `
                    : "min-h-screen fixed -left-full z-40 transition-all ease-in-out "
            }
        >
            <div
                className={
                    isDark
                        ? "w-full min-h-screen bg-gray-900 text-white"
                        : "w-full min-h-screen bg-slate-400 text-dark/95"
                }
            >
                <div className="pt-10 pb-14">
                    <ul
                        className="w-full flex flex-col gap-3 p-2 pb-14"
                        dir="rtl"
                        style={{ height: "70vh", overflowY: "auto" }}
                    >
                        <NavLink
                            to="/dachboard"
                            className={` hover:bg-slate-900 rounded-lg `}
                        >
                            <li
                                className="flex gap-2 text-lg duration-200 h-full w-full hover:text-red-400 rounded-tl-xl  rounded-bl-xl cursor-pointer p-3"
                                onClick={() => {
                                    if (window.innerWidth < 600) {
                                        toggleMenu();
                                    }
                                }}
                            >
                                <span>
                                    <AiFillHome />
                                </span>
                                <p
                                    className={
                                        !isShow
                                            ? `opacity-0 group-hover:opacity-100 duration-100`
                                            : "opacity-100 duration-100"
                                    }
                                >
                                    الرئيسية
                                </p>
                            </li>
                        </NavLink>

                        <NavLink
                            to="/dachboard/vendors"
                            className={` hover:bg-slate-900 rounded-lg`}
                        >
                            <li
                                className="flex gap-2 text-lg duration-200 h-full w-full hover:text-red-400 rounded-tl-xl  rounded-bl-xl cursor-pointer p-3"
                                onClick={() => {
                                    if (window.innerWidth < 600) {
                                        toggleMenu();
                                    }
                                }}
                            >
                                <span>
                                    <AiFillHome />
                                </span>
                                <p
                                    className={
                                        !isShow
                                            ? `opacity-0 group-hover:opacity-100 duration-100`
                                            : "opacity-100 duration-100"
                                    }
                                >
                                    التجار
                                </p>
                            </li>
                        </NavLink>

                        <NavLink
                            to="/dachboard/customers"
                            className={` hover:bg-slate-900 rounded-lg`}
                        >
                            <li
                                className="flex gap-2 text-lg duration-200 h-full w-full hover:text-red-400 rounded-tl-xl  rounded-bl-xl cursor-pointer p-3"
                                onClick={() => {
                                    if (window.innerWidth < 600) {
                                        toggleMenu();
                                    }
                                }}
                            >
                                <span>
                                    <AiFillHome />
                                </span>
                                <p
                                    className={
                                        !isShow
                                            ? `opacity-0 group-hover:opacity-100 duration-100`
                                            : "opacity-100 duration-100"
                                    }
                                >
                                    العملاء
                                </p>
                            </li>
                        </NavLink>

                        <NavLink
                            to="/dachboard/users"
                            className={` hover:bg-slate-900 rounded-lg`}
                        >
                            <li
                                className="flex gap-2 text-lg duration-200 h-full w-full hover:text-red-400 rounded-tl-xl  rounded-bl-xl cursor-pointer p-3"
                                onClick={() => {
                                    if (window.innerWidth < 600) {
                                        toggleMenu();
                                    }
                                }}
                            >
                                <span>
                                    <AiFillHome />
                                </span>
                                <p
                                    className={
                                        !isShow
                                            ? `opacity-0 group-hover:opacity-100 duration-100`
                                            : "opacity-100 duration-100"
                                    }
                                >
                                    المستخدمين
                                </p>
                            </li>
                        </NavLink>

                        <NavLink
                            to="/dachboard/addAdvertisement"
                            className={` hover:bg-slate-900 rounded-lg`}
                        >
                            <li
                                className="flex gap-2 text-lg duration-200 h-full w-full hover:text-red-400 rounded-tl-xl  rounded-bl-xl cursor-pointer p-3"
                                onClick={() => {
                                    if (window.innerWidth < 600) {
                                        toggleMenu();
                                    }
                                }}
                            >
                                <span>
                                    <AiFillHome />
                                </span>
                                <p
                                    className={
                                        !isShow
                                            ? `opacity-0 group-hover:opacity-100 duration-100`
                                            : "opacity-100 duration-100"
                                    }
                                >
                                    اضافه اعلانات
                                </p>
                            </li>
                        </NavLink>

                        <NavLink
                            to="/dachboard/adjustAddings"
                            className={` hover:bg-slate-900 rounded-lg`}
                        >
                            <li
                                className="flex gap-2 text-lg duration-200 h-full w-full hover:text-red-400 rounded-tl-xl  rounded-bl-xl cursor-pointer p-3"
                                onClick={() => {
                                    if (window.innerWidth < 600) {
                                        toggleMenu();
                                    }
                                }}
                            >
                                <span>
                                    <AiFillHome />
                                </span>
                                <p
                                    className={
                                        !isShow
                                            ? `opacity-0 group-hover:opacity-100 duration-100`
                                            : "opacity-100 duration-100"
                                    }
                                >
                                    تعديل الاضافات
                                </p>
                            </li>
                        </NavLink>
                    </ul>
                </div>
            </div>
        </div>
    );
}
