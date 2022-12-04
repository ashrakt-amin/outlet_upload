import { useFormik } from "formik";
import { Link, useNavigate } from "react-router-dom";
import { MdWavingHand } from "react-icons/md";
import { registerSchema } from "../../components/schemas/register";
import axios from "axios";
import { useState } from "react";
export default function Register() {
    const redirect = useNavigate();

    const [userInfo, setUserInfo] = useState({
        fName: "",
        mName: "",
        lName: "",
        phone: "",
        phone2: "",
        age: "20",
        email: "",
        password: "",
        confirm_password: "",
    });
    const handleChangeInputs = (e) => {
        const value = e.target.value;
        setUserInfo({ ...userInfo, [e.target.name]: value });
    };

    const [rloadBtn, setRloadBtn] = useState(false);
    const [passwordMatch, setPasswordMatch] = useState("");
    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    const [passwordError, setPasswordError] = useState("");

    const [confirmPassError, setConfirmPassError] = useState("");

    const [emailError, setEmailError] = useState("");

    const [phoneError, setPhoneError] = useState("");

    const checkInputs = (e) => {
        e.preventDefault();
        // handleSub();
        let regEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        let regPhone = /^(01)[0-9]{9}$/;

        if (regPhone.test(userInfo.phone2) == false && userInfo.phone2 != "") {
            console.log("not valid");
            setSuccessMsg("الهاتف الثانى غير صحيح");
            setTimeout(() => {
                setSuccessMsg("");
            }, 5000);
            return;
        }

        if (regPhone.test(userInfo.phone) == false) {
            setSuccessMsg("اكتب الهاتف بطريقة صحيحه");
            setTimeout(() => {
                setSuccessMsg("");
            }, 5000);
            return;
        }

        if (regEmail.test(userInfo.email) == false) {
            setSuccessMsg("يجب كتابة الايميل بطريقة صحيحة");
            setTimeout(() => {
                setSuccessMsg("");
            }, 5000);
            return;
        }

        if (userInfo.password.length < 8) {
            setPasswordError("ادخل على الاقل 8 حروف او ارقام او خليط بينهما");
            setTimeout(() => {
                setPasswordError("");
            }, 5000);
            return;
        } else if (userInfo.confirm_password.length < 8) {
            setConfirmPassError(
                "ادخل على الاقل 8 حروف او ارقام او خليط بينهما"
            );
            setTimeout(() => {
                setConfirmPassError("");
            }, 5000);
            return;
        }

        if (userInfo.password !== userInfo.confirm_password) {
            setPasswordMatch("الرقم السرى غير متطابق");
            setTimeout(() => {
                setPasswordMatch("");
            }, 5000);
            return;
        }
        if (
            userInfo.fName.length < 3 ||
            userInfo.mName.length < 3 ||
            userInfo.lName.length < 3
        ) {
            setSuccessMsg("يجب ملئ البيانات");
            setTimeout(() => {
                setSuccessMsg("");
            }, 5000);
        } else {
            console.log("valid");
            handleSub();
        }
    };

    const handleSub = async () => {
        axios.defaults.withCredentials = true;
        setRloadBtn(true);
        // let regPhone = /^(01)[0-9]{9}$/;
        // if (regPhone.test(userInfo.phone2) == false && userInfo.phone2 != "") {
        //     console.log("not valid");
        //     return;
        // }
        axios
            .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
            .then(async (res) => {
                try {
                    await axios
                        .post(
                            `${process.env.MIX_APP_URL}/api/register/clients`,
                            {
                                f_name: userInfo.fName,
                                m_name: userInfo.mName,
                                l_name: userInfo.lName,
                                age: userInfo.age,
                                phone: userInfo.phone,
                                phone2: userInfo.phone2,
                                email: userInfo.email,
                                password: userInfo.password,
                                confirm_password: userInfo.confirm_password,
                            }
                        )
                        .then(async (resp) => {
                            localStorage.setItem(
                                "clTk",
                                JSON.stringify(resp.data.data.token)
                            );
                            setEmailError("");
                            setPhoneError("");
                            setPasswordMatch("");
                            redirect("/");
                        });
                } catch (er) {
                    setRloadBtn(false);
                    if (er.response.data.data?.email) {
                        setEmailError(er.response.data.data?.email[0]);
                    } else if (er.response.data.data?.phone) {
                        setPhoneError(er.response.data.data?.phone[0]);
                    }
                    setTimeout(() => {
                        setEmailError("");
                        setPhoneError("");
                    }, 5000);
                }
            });
    };

    return (
        <div
            className="w-full pb-20 flex flex-col items-center justify-center"
            dir="rtl"
        >
            {/* <div className="header-one-div mb-3 w-full">
                <HeaderOne />
            </div> */}

            <div className="register-container flex justify-center px-2 w-full">
                <div className="flex flex-col relative items-center bg-white/90 w-full md:w-[75%] p-5 rounded-lg">
                    <div className="flex items-center justify-center text-xl md:text-3xl gap-1">
                        <h2 className="font-semibold">مرحبا بك</h2>
                        <span className="text-slate-600 select-none">
                            <MdWavingHand />
                        </span>
                    </div>
                    {successMsg.length > 0 && (
                        <div className="msg absolute text-center top-10 left-0 w-full rounded-md p-2 bg-green-400">
                            {successMsg}
                        </div>
                    )}

                    <form className="flex flex-col items-center gap-3 mt-6 w-full">
                        {successMsg.length > 0 && (
                            <div className="msg fixed text-center top-56 left-0 w-full rounded-md p-2 bg-green-400">
                                {successMsg}
                            </div>
                        )}

                        <div className="api-errors-div-clientRegister bg-red-500 fixed text-center top-56 left-0 w-full rounded-md">
                            {phoneError.length > 0 && (
                                <div className="msg text-center text-white">
                                    {phoneError}
                                </div>
                            )}
                            {emailError.length > 0 && (
                                <div className="msg text-center text-white">
                                    {emailError}
                                </div>
                            )}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 w-full lg:w-[75%] gap-6">
                            <span>
                                <span className="text-xs">الاسم الاول</span>
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
                                <span className="text-xs">الاسم الثانى</span>
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
                                <span className="text-xs">الاسم الاخير</span>
                                <input
                                    name="lName"
                                    type="text"
                                    className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                    placeholder="اسم المستخدم الثالث"
                                    onChange={handleChangeInputs}
                                    value={userInfo.lName}
                                />
                            </span>
                            <span>
                                <span className="text-xs">العمر</span>
                                <input
                                    name="age"
                                    type="number"
                                    className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                    placeholder="اكتب عمرك"
                                    onChange={handleChangeInputs}
                                    value={userInfo.age}
                                />
                            </span>
                            <span>
                                <span className="text-xs">
                                    البريد الالكترونى
                                </span>
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
                                <span className="text-xs">رقم الهاتف</span>
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
                                <span className="text-xs">
                                    رقم الهاتف 2 (إختيارى)
                                </span>
                                <input
                                    type="tel"
                                    id="phone2"
                                    name="phone2"
                                    className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                    placeholder="2 رقم الهاتف"
                                    onChange={handleChangeInputs}
                                    value={userInfo.phone2}
                                />
                            </span>
                            <span className="relative">
                                <span className="text-xs">الرقم السرى</span>
                                <input
                                    type="password"
                                    name="password"
                                    className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                    placeholder="كلمة المرور"
                                    onChange={handleChangeInputs}
                                    value={userInfo.password}
                                />
                                {passwordError.length > 0 && (
                                    <div className="absolute top-0 left-0 bg-red-400 w-fit text-white text-sm ">
                                        {passwordError}
                                    </div>
                                )}
                            </span>
                            <div className="relative">
                                <span className="text-xs">
                                    تأكيد الرقم السرى
                                </span>
                                <input
                                    type="password"
                                    name="confirm_password"
                                    className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                    placeholder="تأكيد كلمة المرور"
                                    onChange={handleChangeInputs}
                                    value={userInfo.confirm_password}
                                />
                                {passwordMatch.length > 0 && (
                                    <div className="absolute top-0 -left-0 bg-red-400 w-fit text-white text-sm ">
                                        {passwordMatch}
                                    </div>
                                )}
                                {confirmPassError.length > 0 && (
                                    <div className="absolute top-0 left-0 bg-red-400 w-fit text-white text-sm ">
                                        {confirmPassError}
                                    </div>
                                )}
                            </div>
                        </div>
                        <div className="flex items-center justify-center w-1/2 my-2">
                            {!rloadBtn ? (
                                <input
                                    onClick={checkInputs}
                                    type="submit"
                                    value="تسجيل الدخول"
                                    className="bg-[#9155FD] py-2 px-4 w-full text-white text-lg font-semibold rounded-lg cursor-pointer"
                                />
                            ) : (
                                "يتم تسجيل الدخول الان"
                            )}
                        </div>
                    </form>
                    <div className="flex items-center justify-center my-4">
                        <p className="flex gap-2 items-center text-lg">
                            <span>لديك حساب بالفعل؟</span>
                            <span className="text-[#9155FD]">
                                <Link to="/clientLogin">قم بتسجيل الدخول</Link>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
