import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";
import { useFormik } from "formik";
// import { loginSchema } from "../../components/schemas/login";

import { loginSchema } from "../../components/schemas/login";

import React, { useEffect, useState } from "react";
import axios from "axios";

import HeaderOne from "../HeaderOne/HeaderOne";

const ClinetLogin = () => {
    const redirect = useNavigate();

    const [loginErr, setLoginErr] = useState("");

    const [realoadBtn, setRealoadBnt] = useState(false);

    const [hideLogInBtn, setHideLogInBtn] = useState(false);

    const [clientInfo, setClientInfo] = useState({ phone: "", password: "" });

    const handleChangeInputs = (e) => {
        const value = e.target.value;
        setClientInfo({ ...clientInfo, [e.target.name]: value });
    };

    // useEffect(() => {
    //     if (localStorage.getItem("clTk")) {
    //         // redirect("/");
    //     }
    // }, []);

    const loginFunc = async (e) => {
        e.preventDefault();
        let regPhone = /^(01)[0-9]{9}$/;
        if (regPhone.test(clientInfo.phone) == false) {
            setLoginErr("اكتب الهاتف بطريقة صحيحه");
            setTimeout(() => {
                setLoginErr("");
            }, 5000);
            return;
        }
        if (clientInfo.password.length < 8) {
            setLoginErr("ادخل على الاقل 8 حروف او ارقام او خليط بينهما");
            setTimeout(() => {
                setLoginErr("");
            }, 5000);
            return;
        }
        axios.defaults.withCredentials = true;
        if (clientInfo.phone.length == 11 && clientInfo.password.length >= 4) {
            setRealoadBnt(true);
            try {
                await axios
                    .post(`${process.env.MIX_APP_URL}/api/login/clients`, {
                        phone: clientInfo.phone,
                        password: clientInfo.password,
                    })
                    .then(async (resp) => {
                        setHideLogInBtn(!hideLogInBtn);
                        console.log(resp);
                        localStorage.setItem(
                            "clTk",
                            JSON.stringify(resp.data.data.token)
                        );
                        redirect("/");
                        setRealoadBnt(true);
                    });
            } catch (er) {
                setRealoadBnt(false);
                setLoginErr(er.response.data.data.error);
                setTimeout(() => {
                    setLoginErr("");
                }, 5000);
            }
            // axios
            //     .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
            //     .then(async (res) => {
            //         try {
            //             await axios
            //                 .post(`${process.env.MIX_APP_URL}/api/login/clients`, {
            //                     phone: clientInfo.phone,
            //                     password: clientInfo.password,
            //                 })
            //                 .then(async (resp) => {
            //                     setHideLogInBtn(!hideLogInBtn);
            //                     console.log(resp);
            //                     localStorage.setItem(
            //                         "clTk",
            //                         JSON.stringify(resp.data.data.token)
            //                     );
            //                     redirect("/");
            //                     setRealoadBnt(true);
            //                 });
            //         } catch (er) {
            //             setRealoadBnt(false);
            //             setLoginErr(er.response.data.data.error);
            //             setTimeout(() => {
            //                 setLoginErr("");
            //             }, 5000);
            //         }
            //     });
        }
    };

    // const { values, errors, touched, handleBlur, handleChange, handleSubmit } =
    //     useFormik({
    //         initialValues: {
    //             email: "",
    //             password: "",
    //         },
    //         validationSchema: loginSchema,
    //         onSubmit: async (values, actions) => {
    //         },
    //     });

    // useEffect(() => {
    // }, [])

    return (
        <div
            dir="rtl"
            className="w-full pb-5 flex flex-col items-center justify-center"
        >
            {/* <div className="header-one-div mb-3 w-full">
                <HeaderOne />
            </div> */}
            <div className="flex flex-col relative items-center bg-white/90 shadow-xl w-full md:w-1/2 p-5 rounded-lg">
                {loginErr.length > 0 && (
                    <div className="absolute top-0 text-white p-3 text-center w-full left-0 bg-red-400">
                        {loginErr}
                    </div>
                )}
                <div className="flex items-center  justify-center text-xl md:text-3xl gap-1">
                    <h2 className="font-semibold">مرحبا بك</h2>
                    <span className="text-slate-600 select-none">
                        <MdWavingHand />
                    </span>
                </div>
                <form
                    className="flex flex-col relative items-center gap-3 mt-6 w-full"
                    // onSubmit={handleSubmit}
                    // method="post"
                >
                    <span>رقم الهاتف </span>
                    <input
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                        placeholder="ادخل رقم الهاتف"
                        type="tel"
                        name="phone"
                        onChange={handleChangeInputs}
                        // onBlur={handleBlur}
                        value={clientInfo.phone}
                    />
                    {/* <p className="text-red-700">
                        {errors.email && touched.email && errors.email}
                    </p> */}
                    <span>الرقم السرى</span>
                    <input
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                        placeholder="كلمة المرور"
                        type="password"
                        name="password"
                        onChange={handleChangeInputs}
                        // onBlur={handleBlur}
                        value={clientInfo.password}
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
                    {!hideLogInBtn && (
                        <div className="flex items-center justify-center w-full my-2">
                            {!realoadBtn ? (
                                <input
                                    onClick={loginFunc}
                                    type="submit"
                                    value="تسجيل الدخول"
                                    className="bg-[#9155FD] py-2 px-4 w-full text-white text-lg font-semibold rounded-lg cursor-pointer"
                                />
                            ) : (
                                "انتظر من فضلك....."
                            )}
                        </div>
                    )}
                </form>
                <div className="flex items-center justify-center my-4">
                    <p className="flex gap-2 items-center text-lg">
                        <span>ليس لديك حساب؟</span>
                        <span className="text-[#9155FD]">
                            <Link to="/clientRegister">
                                قم بإنشاء حساب جديد
                            </Link>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default ClinetLogin;
