import React from "react";
import axios from "axios";
import { useEffect, useState } from "react";
import { parseInt } from "lodash";

function AddTrader({ closeModal, getTradersAgain }) {
    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    const [fName, setfName] = useState("");
    const [mName, setmName] = useState("");
    const [lName, setlName] = useState("");
    const [age, setAge] = useState("");
    const [phone, setPhone] = useState("");
    const [nationalId, setNationalId] = useState("");
    const [phone2, setPhone2] = useState("");
    const [imgVal, setImgVal] = useState("");

    const [email, setEmail] = useState("");
    const [traderCode, setTraderCode] = useState("");

    // (check state)
    const [checkFName, setcheckFName] = useState("");
    const [checkMName, setcheckMName] = useState("");
    const [checkLName, setcheckLName] = useState("");
    const [checkAge, setcheckAge] = useState("");
    const [checkPhone, setcheckPhone] = useState("");
    const [checkNationalId, setcheckNationalId] = useState("");
    const [checkEmail, setcheckEmail] = useState("");
    const [checkTraderCode, setcheckTraderCode] = useState("");
    // (check state)

    const inputsValid = () => {
        let regEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        let regPhone = /^(01)[0-9]{9}$/;

        console.log(fName);
        if (fName.length <= 3) {
            setcheckFName("التاكد من الاسم الاول");
            return;
        } else {
            setcheckFName("");
        }

        if (mName.length < 2) {
            setcheckMName("التاكد من الاسم الثانى");
            return;
        } else {
            setcheckMName("");
        }

        if (lName.length < 2) {
            setcheckLName("التاكد من الاسم الثالث");
            return;
        }
        setcheckLName("");

        if (age.length == 0) {
            setcheckAge("التاكد من العمر");
            return;
        } else {
            setcheckAge("");
        }

        if (regPhone.test(phone) == false) {
            setcheckPhone("تاكد من الهاتف");
            return;
        } else {
            setcheckPhone("");
        }

        if (nationalId.length != 14) {
            setcheckNationalId("الرقم القومى غير صحيح");
            return;
        } else {
            setcheckNationalId("");
        }

        if (regEmail.test(email) == false && email.length > 0) {
            setcheckEmail("الايميل خطا");
            return;
        } else {
            setcheckEmail("");
        }

        if (traderCode == "") {
            setcheckTraderCode("الكود مكون من ارقام فقط");
            return;
        }
        setcheckTraderCode("");

        if (imgVal == "") {
            setSuccessMsg("ادخل صورة المحل");
            return;
        }
        setSuccessMsg("");

        postData();
    };

    const emptyInputs = () => {
        setfName("");
        setmName("");
        setlName("");
        setAge("");
        setPhone("");
        setNationalId("");
        setPhone2("");
        setEmail("");
        setTraderCode("");
        setImgVal("");
    };

    console.log(age);
    const postData = async () => {
        const getUserToken = JSON.parse(localStorage.getItem("uTk"));

        const fromData = new FormData();
        console.log(fName);
        fromData.append("f_name", fName);
        fromData.append("m_name", mName);
        fromData.append("l_name", lName);
        fromData.append("age", age);
        fromData.append("phone", phone);
        fromData.append("phone2", phone2);
        fromData.append("national_id", nationalId);
        fromData.append("email", email);
        fromData.append("code", traderCode);
        fromData.append("logo", imgVal);
        try {
            let res = await axios.post(
                `${process.env.MIX_APP_URL}/api/traders`,
                fromData,
                {
                    headers: {
                        Authorization: `Bearer ${getUserToken}`,
                    },
                }
            );
            console.log(res);
            emptyInputs();
            setSuccessMsg(res.data.message);
            getTradersAgain();
            setTimeout(() => {
                setSuccessMsg("");
            }, 3000);
        } catch (er) {
            console.log(er);
            setSuccessMsg(er.response.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 3000);
        }
    };

    const handleImg = (e) => {
        setImgVal(e.target.files[0]);
    };

    return (
        <div
            id="defaultModal"
            tabIndex="-1"
            className="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 bottom-0 z-50 w-full md:inset-0 h-screen justify-center items-center flex bg-slate-800/75"
            aria-modal="true"
            role="dialog"
            dir="rtl"
        >
            <div className="relative p-4 w-full max-w-4xl h-full md:h-auto">
                <div className="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-28">
                    <div className="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                        <h3 className="text-xl font-semibold text-gray-900 dark:text-white">
                            إضافة تاجر
                        </h3>

                        <button
                            type="button"
                            className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            onClick={() => closeModal()}
                        >
                            <svg
                                aria-hidden="true"
                                className="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fillRule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clipRule="evenodd"
                                ></path>
                            </svg>
                            <span className="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div className="p-6 space-y-6">
                        <form className="flex flex-col items-center gap-3 mt-6 dark:text-black relative">
                            {successMsg.length > 0 && (
                                <div className="msg fixed text-center z-50 top-28 left-0 w-full rounded-md p-2 bg-green-400">
                                    {successMsg}
                                </div>
                            )}
                            <div className="grid grid-cols-1 md:grid-cols-2 w-full gap-8">
                                <div className="relative ">
                                    <h1>الاسم الاول</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="اسم التاجر الاول"
                                        value={fName}
                                        onChange={(e) =>
                                            setfName(e.target.value)
                                        }
                                    />
                                    {checkFName.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkFName}
                                        </span>
                                    )}
                                </div>
                                {/* <span className="bg-red-400 p-2 rounded-lg">{inputsValidMessage}</span> */}

                                <div className="relative ">
                                    <h1>الاسم الثانى</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الاسم التاجر الاوسط"
                                        value={mName}
                                        onChange={(e) =>
                                            setmName(e.target.value)
                                        }
                                    />
                                    {checkMName.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkMName}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>الاسم الثالث</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الاسم الثالث"
                                        value={lName}
                                        onChange={(e) =>
                                            setlName(e.target.value)
                                        }
                                    />
                                    {checkLName.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkLName}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>تاريخ الميلاد</h1>
                                    <input
                                        type="date"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="العمر"
                                        value={age}
                                        onChange={(e) => setAge(e.target.value)}
                                    />
                                    {checkAge.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkAge}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>التليفون الاول</h1>
                                    <input
                                        type="tel"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="التليفون الاول"
                                        value={phone}
                                        onChange={(e) =>
                                            setPhone(e.target.value)
                                        }
                                    />
                                    {checkPhone.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkPhone}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>الرقم القومى</h1>
                                    <input
                                        type="number"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الرقم القومى"
                                        value={nationalId}
                                        onChange={(e) =>
                                            setNationalId(e.target.value)
                                        }
                                    />
                                    {checkNationalId.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkNationalId}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>التليفون الثانى</h1>
                                    <input
                                        type="tel"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="التليفون الثانى"
                                        value={phone2}
                                        onChange={(e) =>
                                            setPhone2(e.target.value)
                                        }
                                    />
                                </div>

                                <div className="relative ">
                                    <h1>الايميل</h1>
                                    <input
                                        type="email"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الايميل"
                                        value={email}
                                        onChange={(e) =>
                                            setEmail(e.target.value)
                                        }
                                    />
                                    {checkEmail.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkEmail}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>كود التاجر</h1>
                                    <input
                                        type="number"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الكود"
                                        value={traderCode}
                                        onChange={(e) =>
                                            setTraderCode(e.target.value)
                                        }
                                    />
                                    {checkTraderCode.length > 0 && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkTraderCode}
                                        </span>
                                    )}
                                </div>

                                <div className="relative ">
                                    <h1>إختر صورة المحل</h1>
                                    <input
                                        onChange={handleImg}
                                        type="file"
                                        name="traderimg"
                                        id="traderlogo"
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div className="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                        <button
                            data-modal-toggle="defaultModal"
                            type="button"
                            className="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 ml-2"
                            onClick={closeModal}
                        >
                            إلغاء
                        </button>
                        <button
                            data-modal-toggle="defaultModal"
                            type="button"
                            className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            onClick={inputsValid}
                        >
                            إضافة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default AddTrader;
