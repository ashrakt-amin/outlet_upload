import axios from "axios";
import React, { useEffect, useState } from "react";

import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";

const UsersPage = () => {
    const [allUsers, setAllUsers] = useState([]);

    const [successMsg, setSuccessMsg] = useState("");

    // ------------------------------- (get users) -------------------------------
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getAllUsers = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/users`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                console.log(res);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getAllUsers();
        return () => {
            cancelRequest.cancel();
        };
    }, []);
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

    const emptyInputs = () => {
        userInfo.fName = "";
        userInfo.mName = "";
        userInfo.lName = "";
        userInfo.email = "";
        userInfo.phone = "";
        userInfo.password = "";
        userInfo.confrimePassword = "";
    };

    const handleSub = async (e) => {
        let uToken = JSON.parse(localStorage.getItem("uTk"));
        e.preventDefault();

        let regPhone = /^(01)[0-9]{9}$/;
        if (regPhone.test(userInfo.phone) == false) {
            setSuccessMsg("اكتب الهاتف بطريقة صحيحة");
            setTimeout(() => {
                setSuccessMsg("");
            }, 3000);
            return;
        }

        if (userInfo.password.length < 8) {
            setSuccessMsg(
                "يجب ان يكون رقم المرور اكبر من او يساوى 8 ارقام او حروف "
            );
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }

        if (userInfo.confrimePassword.length < 8) {
            setSuccessMsg(
                "يجب ان يكون  تاكيد رقم المرور اكبر من او يساوى 8 ارقام او حروف "
            );
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }

        if (userInfo.confrimePassword != userInfo.password) {
            setSuccessMsg("كلمة المرور غير متشابهة صححها اولا");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }

        try {
            let res = await axios.post(
                `${process.env.MIX_APP_URL}/api/register/users`,
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
                    // withCredentials: true,
                    headers: {
                        Authorization: `Bearer ${uToken}`,
                    },
                }
            );
            setSuccessMsg(res.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 3000);
            emptyInputs();
        } catch (er) {
            console.log(er);
        }
    };
    // ------------------------------- (add users) -------------------------------

    return (
        <div className="w-full px-4 py-10 flex flex-col items-center" dir="rtl">
            <div className="flex flex-col items-center bg-white/90 w-full md:w-[75%] p-5 rounded-lg">
                {successMsg.length > 0 && (
                    <div className="bg-green-500 text-center text-white fixed top-36 left-0 w-full z-50 p-3 rounded-md">
                        {successMsg}
                    </div>
                )}

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
