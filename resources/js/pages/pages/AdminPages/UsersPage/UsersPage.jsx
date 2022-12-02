import axios from "axios";
import React, { useEffect, useState } from "react";

import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";

const UsersPage = () => {
    const [allUsers, setAllUsers] = useState([]);

    // ------------------------------- (get users) -------------------------------
    // useEffect(() => {
    //     const cancelRequest = axios.CancelToken.source();
    //     const getAllUsers = async () => {
    //         try {
    //             const res = await axios.get(`http://127.0.0.1:8000/api/users`, {
    //                 cancelRequest: cancelRequest.token,
    //             });
    //             console.log(res);
    //         } catch (error) {
    //             console.warn(error.message);
    //         }
    //     };
    //     getAllUsers();
    //     return () => {
    //         cancelRequest.cancel();
    //     };
    // }, []);
    // ------------------------------- (get users) -------------------------------

    // ------------------------------- (user state) -------------------------------
    const [userInfo, setUserInfo] = useState({
        fName: "",
        mName: "",
        lName: "",
        email: "",
        phone: "",
        password: "",
        confrimePassword: "",
    });
    const handleChangeInputs = (e) => {
        const value = e.target.value;
        setUserInfo({ ...userInfo, [e.target.name]: value });
    };
    // ------------------------------- (add users) -------------------------------

    const handleSub = async (e) => {
        let uToken = JSON.parse(localStorage.getItem("uTk"));
        e.preventDefault();
        await axios
            .post(
                "http://127.0.0.1:8000/api/register/users",
                {
                    f_name: userInfo.fName,
                    l_name: userInfo.lName,
                    m_name: userInfo.mName,
                    email: userInfo.email,
                    phone: userInfo.phone,
                    password: userInfo.password,
                    confirm_password: userInfo.confrimePassword,
                },
                {
                    withCredentials: true,
                    headers: {
                        Authorization: `Bearer ${uToken}`,
                    },
                }
            )
            .then((res) => {
                console.log(res);
            });
    };
    // ------------------------------- (add users) -------------------------------

    return (
        <div className="w-full px-4 py-10 flex flex-col items-center" dir="rtl">
            <div className="flex flex-col items-center bg-white/90 w-full md:w-[75%] p-5 rounded-lg">
                <div className="flex items-center justify-center text-xl md:text-3xl gap-1">
                    <h2 className="font-semibold">مرحبا بك</h2>
                    <span className="text-slate-600 select-none">
                        <MdWavingHand />
                    </span>
                </div>
                <form
                    className="flex flex-col items-center gap-3 mt-6 w-full"
                    method="post"
                    onSubmit={handleSub}
                >
                    <div className="grid grid-cols-1 md:grid-cols-2 w-full lg:w-[75%] gap-6">
                        <span>
                            <input
                                name="fName"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اسم الاول"
                                onChange={handleChangeInputs}
                                value={userInfo.fName}
                            />
                        </span>
                        <span>
                            <input
                                name="mName"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اسم الثانى"
                                onChange={handleChangeInputs}
                                value={userInfo.mName}
                            />
                        </span>
                        <span>
                            <input
                                name="lName"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اسم الثالث"
                                onChange={handleChangeInputs}
                                value={userInfo.lName}
                            />
                        </span>
                        <span>
                            <input
                                type="email"
                                name="email"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="البريد الالكتروني"
                                onChange={handleChangeInputs}
                                value={userInfo.email}
                            />
                        </span>
                        <span>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="رقم الهاتف"
                                onChange={handleChangeInputs}
                                value={userInfo.phone}
                            />
                        </span>
                        <span>
                            <input
                                type="password"
                                name="password"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="كلمة المرور"
                                onChange={handleChangeInputs}
                                value={userInfo.password}
                            />
                        </span>
                        <span>
                            <input
                                type="password"
                                name="confrimePassword"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="تأكيد كلمة المرور"
                                onChange={handleChangeInputs}
                                value={userInfo.confrimePassword}
                            />
                        </span>
                    </div>
                    <div className="flex items-center justify-center w-1/2 my-2">
                        <input
                            type="submit"
                            value="إضافة مستخدم"
                            className="bg-[#9155FD] py-2 px-4 w-full text-white text-lg font-semibold rounded-lg cursor-pointer"
                        />
                    </div>
                </form>
            </div>

            <h1>المستخدمين</h1>
        </div>
    );
};

export default UsersPage;
