import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";
import { useFormik } from "formik";
// import { loginSchema } from "../../components/schemas/login";

import { loginSchema } from "../../components/schemas/login";

import axios from "axios";

const TraderLogin = () => {
    const redirect = useNavigate();

    const [userInfo, setUserInfo] = useState({ phone: "", password: "" });

    const [loginErr, setLoginErr] = useState("");

    const [reloadBtn, setReloadBtn] = useState(false);

    const handleChangeInputs = (e) => {
        const value = e.target.value;
        setUserInfo({ ...userInfo, [e.target.name]: value });
    };

    // const { values, errors, touched, handleBlur, handleChange, handleSubmit } =
    //     useFormik({
    //         initialValues: {
    //             email: "",
    //             password: "",
    //         },
    //         validationSchema: loginSchema,
    //         onSubmit: async (values, actions) => {
    //             try {
    //                 await axios
    //                     .post("${process.env.MIX_APP_URL}/api/login/traders", {
    //                         email: values.email,
    //                         password: values.password,
    //                     })
    //                     .then((res) => {
    //                         console.log(res);
    //                         if (res.data.success == true) {
    //                             localStorage.setItem(
    //                                 "trTk",
    //                                 JSON.stringify(res.data.data.token)
    //                             );
    //                             redirect("/trader/dachboard");
    //                         }
    //                     });
    //             } catch (er) {
    //                 setLoginErr(er.response.data.data.error);
    //             }
    //         },
    //     });

    const checkInputs = (e) => {
        e.preventDefault();
        let regPhone = /^(01)[0-9]{9}$/;
        if (
            regPhone.test(userInfo.phone) == false ||
            userInfo.password.length < 6
        ) {
            setLoginErr("يجب ملئ البيانات بطريقة صحيحه");
            setTimeout(() => {
                setLoginErr("");
            }, 4000);
        } else {
            console.log("valid");
            submitFunc();
        }
    };

    const submitFunc = async () => {
        setReloadBtn(true);
        try {
            await axios
                .post("${process.env.MIX_APP_URL}/api/login/traders", {
                    phone: userInfo.phone,
                    password: userInfo.password,
                })
                .then((res) => {
                    console.log(res.data.data);
                    if (res.data.success == true) {
                        localStorage.setItem(
                            "trTk",
                            JSON.stringify(res.data.data.token)
                        );
                        setReloadBtn(false);
                        redirect("/trader/dachboard");
                    }
                });
        } catch (er) {
            setLoginErr(er.response.data.data.error);
            setReloadBtn(false);
            setTimeout(() => {
                setLoginErr("");
            }, 4000);
        }
    };
    return (
        <div
            dir="rtl"
            className="w-full px-4 py-10 flex items-center justify-center"
        >
            {loginErr.length > 0 && (
                <div className="p-2 text-white top-2 text-center bg-red-500 w-full fixed">
                    {" "}
                    {loginErr}
                </div>
            )}

            {/* <Link
                to={`/`}
                className="absolute bg-red-500 p-1 rounded-md text-white top-1 left-1 "
            >
                إذهب إلى الرئيسية
            </Link> */}
            <div className="flex flex-col items-center bg-white/90 shadow-xl w-full md:w-1/2 p-5 rounded-lg">
                <div className="flex items-center justify-center text-xl md:text-3xl gap-1">
                    <h2 className="font-semibold">مرحبا بك</h2>
                    <span className="text-slate-600 select-none">
                        <MdWavingHand />
                    </span>
                </div>
                <form
                    className="flex flex-col items-center gap-3 mt-6 w-full"
                    // onSubmit={handleSubmit}
                    // method="post"
                >
                    <span>رقم الهاتف</span>
                    <input
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                        placeholder="ادخل رقم التليفون"
                        type="tel"
                        name="phone"
                        onChange={handleChangeInputs}
                        value={userInfo.phone}
                    />
                    <span>الرقم السرى</span>
                    <input
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                        placeholder="كلمة المرور"
                        type="password"
                        name="password"
                        onChange={handleChangeInputs}
                        value={userInfo.password}
                    />
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
                    {!reloadBtn ? (
                        <div className="flex items-center justify-center w-full my-2">
                            <button
                                onClick={checkInputs}
                                type="submit"
                                value="تسجيل الدخول"
                                className="bg-[#9155FD] py-2 px-4 w-full text-white text-lg font-semibold rounded-lg cursor-pointer"
                            >
                                دخول
                            </button>
                        </div>
                    ) : (
                        "إنتظر من فضلك"
                    )}
                </form>
                <div className="flex items-center justify-center my-4">
                    <p className="flex gap-2 items-center text-lg">
                        <span>ليس لديك حساب؟</span>
                        <span className="text-[#9155FD]">
                            <Link to="/traderRegister">
                                قم بإنشاء حساب جديد
                            </Link>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default TraderLogin;
