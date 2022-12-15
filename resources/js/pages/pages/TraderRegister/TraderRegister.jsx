import { useFormik } from "formik";
import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";

import axios from "axios";
import { useState } from "react";
export default function TraderRegister() {
    const redirect = useNavigate();

    const [errorRegister, setErrorRegsiter] = useState("");

    const [reloadBtn, setReloadBtn] = useState(false);

    const [userInfo, setUserInfo] = useState({
        phone: "",
        code: "",
        age: "",
        password: "",
        confirm_password: "",
    });

    const handleChangeInputs = (e) => {
        const value = e.target.value;
        setUserInfo({ ...userInfo, [e.target.name]: value });
    };

    const checkInputs = (e) => {
        e.preventDefault();

        const codeValidation = /^[0-9]/;

        if (
            userInfo.password.length < 8 ||
            userInfo.confirm_password.length < 8
        ) {
            setErrorRegsiter("يجب ان يكون الرقم السرى 8 حروف او اكثر  ");
            setTimeout(() => {
                setErrorRegsiter("");
            }, 4000);
            return;
        }

        let regPhone = /^(01)[0-9]{9}$/;
        if (regPhone.test(userInfo.phone) == false) {
            setErrorRegsiter("اكتب الهاتف بطريقة صحيحة");
            setTimeout(() => {
                setErrorRegsiter("");
            }, 4000);
            return;
        }

        if (codeValidation.test(userInfo.code) == false) {
            setErrorRegsiter("اكتب الكود");
            setTimeout(() => {
                setErrorRegsiter("");
            }, 2000);
            return;
        }

        if (userInfo.password === userInfo.confirm_password) {
            setErrorRegsiter("");
            handleSub();
        } else {
            setErrorRegsiter("الرقم السرى غير متطابق");
            setTimeout(() => {
                setErrorRegsiter("");
            }, 4000);
        }
    };

    const handleSub = async () => {
        const fData = new FormData();

        fData.append("phone", userInfo.phone);
        fData.append("code", userInfo.code);
        fData.append("password", userInfo.password);
        fData.append("confirm_password", userInfo.confirm_password);

        axios.defaults.withCredentials = true;
        axios
            .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
            .then(async (res) => {
                try {
                    setReloadBtn(true);
                    await axios
                        .post(
                            `${process.env.MIX_APP_URL}/api/register/traders`,
                            fData
                        )
                        .then(async (resp) => {
                            console.log(resp);
                            setErrorRegsiter("");
                            setReloadBtn(true);
                            localStorage.setItem(
                                "trTk",
                                JSON.stringify(resp.data.data.token)
                            );
                            redirect("/trader/dachboard");
                        });
                } catch (er) {
                    setReloadBtn(false);
                    console.log(er.response);
                    setErrorRegsiter(er.response.data.message);
                    setTimeout(() => {
                        setErrorRegsiter("");
                    }, 4000);
                }
            });
    };

    return (
        <div
            className="w-full px-4 py-10 flex items-center justify-center"
            dir="rtl"
        >
            <div className="flex flex-col items-center bg-white/90 w-full md:w-[75%] p-5 rounded-lg">
                {errorRegister.length > 0 && (
                    <div className="absolute text-sm bg-red-600 w-100 top-28 left-0 w-full p-1 text-center text-white rounded-md">
                        {errorRegister}
                    </div>
                )}
                <div className="flex relative items-center justify-center text-xl md:text-3xl gap-1">
                    <h2 className="font-semibold cursor-pointer">
                        مرحبا بك أيها التاجر العزيز
                    </h2>
                    <span className="text-slate-600 select-none">
                        <MdWavingHand />
                    </span>
                </div>
                <form className="flex flex-col items-center gap-3 mt-6 w-full">
                    <div className="grid grid-cols-1 md:grid-cols-2 w-full lg:w-[75%] gap-6">
                        {/* <span>
                            <input
                                name="fName"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اسم المستخدم الاول"
                                onChange={handleChangeInputs}
                                value={userInfo.fName}
                            />
                        </span>
                        <span>
                            <input
                                name="mName"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اسم المستخدم الثانى"
                                onChange={handleChangeInputs}
                                value={userInfo.mName}
                            />
                        </span>
                        <span>
                            <input
                                name="lName"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اسم المستخدم الثالث"
                                onChange={handleChangeInputs}
                                value={userInfo.lName}
                            />
                        </span> */}
                        {/* <span>
                            <input
                                name="age"
                                type="text"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="اكتب عمرك"
                                onChange={handleChangeInputs}
                                value={userInfo.age}
                            />
                        </span> */}
                        {/* <span>
                            <input
                                type="email"
                                name="email"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="البريد الالكتروني"
                                onChange={handleChangeInputs}
                                value={userInfo.email}
                            />
                        </span> */}
                        <span>
                            <span className="text-sm">رقم الهاتف</span>
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
                            <span className="text-sm">كلمة المرور</span>
                            <input
                                type="password"
                                name="password"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="يجب ان يكون اكبر من 8 حروف"
                                onChange={handleChangeInputs}
                                value={userInfo.password}
                            />
                        </span>
                        <span>
                            <span className="text-sm">تأكيد كملة المرور</span>
                            <input
                                type="password"
                                name="confirm_password"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="تأكيد كلمة المرور"
                                onChange={handleChangeInputs}
                                value={userInfo.confirm_password}
                            />
                        </span>
                        <span>
                            <span className="text-sm">الكود</span>
                            <input
                                type="text"
                                name="code"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                placeholder="الكود"
                                onChange={handleChangeInputs}
                                value={userInfo.code}
                            />
                        </span>
                    </div>
                    {!reloadBtn ? (
                        <div className="flex items-center justify-center w-1/2 my-2">
                            <button
                                onClick={checkInputs}
                                value="تسجيل الدخول"
                                className="bg-[#9155FD] py-2 px-4 w-full text-white text-lg font-semibold rounded-lg cursor-pointer"
                            >
                                تسجيل
                            </button>
                        </div>
                    ) : (
                        "إنتظر من فضلك"
                    )}
                </form>
                <div className="flex items-center justify-center my-4">
                    <p className="flex gap-2 items-center text-lg">
                        <span>لديك حساب بالفعل؟</span>
                        <span className="text-[#9155FD]">
                            <Link to="/traderLogin">قم بتسجيل الدخول</Link>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    );
}
