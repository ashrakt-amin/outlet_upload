import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";
import { useFormik } from "formik";
import { loginSchema } from "../../../components/schemas/login";
import React, { useState } from "react";
import axios from "axios";
import { useCookies } from "react-cookie";

const AdminLogin = () => {
    const redirect = useNavigate();

    const [successMsg, setsuccessMsg] = useState("");

    const [cookies, setCookie, removeCookie] = useCookies(["user"]);

    const [adminInfo, setAdminInfo] = useState({ phone: "", password: "" });

    const handleChangeInputs = (e) => {
        const value = e.target.value;
        setAdminInfo({ ...adminInfo, [e.target.name]: value });
    };

    const loginAdmin = async (e) => {
        e.preventDefault();

        if (adminInfo.password.length < 8) {
            console.log("not valid");
            return;
        }

        axios
            .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
            .then(async (res) => {
                try {
                    let res = await axios.post(
                        `${process.env.MIX_APP_URL}/api/login/users`,
                        {
                            phone: adminInfo.phone,
                            password: adminInfo.password,
                        }
                    );

                    if (res.data.success == true) {
                        setCookie("user", res.data.data.token);
                        localStorage.setItem(
                            "uTk",
                            JSON.stringify(res.data.data.token)
                        );
                        redirect("/dachboard");
                    }
                } catch (er) {
                    setsuccessMsg(er.response.data.data.error);
                    setTimeout(() => {
                        setsuccessMsg("");
                    }, 3000);
                }
            });
    };

    return (
        <div
            dir="rtl"
            className="w-full px-4 py-10 flex items-center justify-center"
        >
            <div className="flex flex-col items-center bg-white/90 shadow-xl w-full md:w-1/2 p-5 rounded-lg">
                <div className="flex items-center justify-center text-xl md:text-3xl gap-1">
                    <h2 className="font-semibold">مرحبا بك</h2>
                    <span className="text-slate-600 select-none">
                        <MdWavingHand />
                    </span>
                </div>
                {successMsg.length > 0 && (
                    <h1
                        className="p-2 rounded-md bg-red-500
                 text-center text-white"
                    >
                        {successMsg}
                    </h1>
                )}
                <form
                    className="flex flex-col items-center gap-3 mt-6 w-full"
                    // onSubmit={handleSubmit}
                    // method="post"
                >
                    <input
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                        placeholder="رقم التليفون"
                        type="tel"
                        name="phone"
                        onChange={handleChangeInputs}
                        // onBlur={handleBlur}
                        value={adminInfo.phone}
                    />
                    {/* <p className="text-red-700">
                        {errors.email && touched.email && errors.email}
                    </p> */}
                    <input
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                        placeholder="كلمة المرور"
                        type="password"
                        name="password"
                        onChange={handleChangeInputs}
                        // onBlur={handleBlur}
                        value={adminInfo.password}
                    />
                    {/* <p className="text-red-700">
                        {errors.password && touched.password && errors.password}
                    </p> */}
                    {/* <div className="flex items-center justify-between w-full text-lg md:text-xl">
                        <div className="flex items-center gap-1">
                            <p>تذكرني</p>
                            <input
                                type="checkbox"
                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                            />
                        </div>
                        <div className="text-[#9155FD]">
                            <Link to="/">نسيت كلمة المرور؟</Link>
                        </div>
                    </div> */}
                    <div className="flex items-center justify-center w-full my-2">
                        <input
                            onClick={loginAdmin}
                            type="submit"
                            value="تسجيل الدخول"
                            className="bg-[#9155FD] py-2 px-4 w-full text-white text-lg font-semibold rounded-lg cursor-pointer"
                        />
                    </div>
                </form>
                {/* <div className="flex items-center justify-center my-4">
                    <p className="flex gap-2 items-center text-lg">
                        <span>ليس لديك حساب؟</span>
                        <span className="text-[#9155FD]">
                            <Link to="/register">قم بإنشاء حساب جديد</Link>
                        </span>
                    </p>
                </div> */}
            </div>
        </div>
    );
};

export default AdminLogin;
