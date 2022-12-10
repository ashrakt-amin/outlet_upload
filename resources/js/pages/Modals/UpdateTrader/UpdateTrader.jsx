import React from "react";
import axios from "axios";
import { useEffect, useState } from "react";

function UpdateTrader({ closeModal, traderInfo }) {
    const [fName, setfName] = useState("");
    const [mName, setmName] = useState("");
    const [lName, setlName] = useState("");
    const [age, setAge] = useState("");
    const [phone, setPhone] = useState("");
    const [phone2, setPhone2] = useState("");
    const [phone3, setPhone3] = useState("");
    const [phone4, setPhone4] = useState("");
    const [phone5, setPhone5] = useState("");
    const [email, setEmail] = useState("");
    const [traderCode, setTraderCode] = useState("");

    useEffect(() => {
        const {
            f_name,
            m_name,
            l_name,
            age,
            phone,
            phone2,
            phone3,
            phone4,
            phone5,
            email,
            code,
        } = traderInfo;

        setfName(f_name);
        setmName(m_name);
        setlName(l_name);
        setAge(age);
        setPhone(phone);
        setPhone2(phone2 == null ? "" : phone2);
        setPhone3(phone3 == null ? "" : phone3);
        setPhone4(phone4 == null ? "" : phone4);
        setPhone5(phone5 == null ? "" : phone5);
        setEmail(email == null ? "" : email);
        console.log(email);
        setTraderCode(code);
    }, []);

    const updateTraderFunc = async () => {
        try {
            axios
                .put(
                    `${process.env.MIX_APP_URL}/api/traders/${traderInfo.id}`,
                    {
                        f_name: fName,
                        m_name: mName,
                        l_name: lName,
                        age: age,
                        phone: phone,
                        phone2: phone2,
                        phone3: phone3,
                        phone4: phone4,
                        phone5: phone5,
                        email: email,
                        code: traderCode,
                    }
                )
                .then((res) => {
                    console.log(res);
                });
        } catch (error) {}
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
                <div className="relative bg-white rounded-lg shadow dark:bg-gray-700">
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
                            <div className="grid grid-cols-1 md:grid-cols-2 w-full gap-8">
                                <div className="relative ">
                                    <h1>اسم التاجر الاول</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="اسم التاجر"
                                        value={fName}
                                        onChange={(e) =>
                                            setfName(e.target.value)
                                        }
                                    />
                                    {/* {checkName && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkName}
                                        </span>
                                    )} */}
                                </div>
                                {/* <span className="bg-red-400 p-2 rounded-lg">{inputsValidMessage}</span> */}

                                <div className="relative ">
                                    <h1>اسم التاجر الثانى</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الاسم الاوسط"
                                        value={mName}
                                        onChange={(e) =>
                                            setmName(e.target.value)
                                        }
                                    />
                                    {/* {checkMName && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkMName}
                                        </span>
                                    )} */}
                                </div>

                                <div className="relative ">
                                    <h1>اسم التاجر الثالث</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="اللقب"
                                        value={lName}
                                        onChange={(e) =>
                                            setlName(e.target.value)
                                        }
                                    />
                                    {/* {checkLName && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkLName}
                                        </span>
                                    )} */}
                                </div>

                                <div className="relative ">
                                    <h1>العمر</h1>
                                    <input
                                        type="number"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="العمر"
                                        min={18}
                                        value={age}
                                        onChange={(e) => setAge(e.target.value)}
                                    />
                                    {/* {checkage && (
                                        <span className="absolute -bottom-6 right-1 text-sm text-red-400">
                                            {checkage}
                                        </span>
                                    )} */}
                                </div>

                                <div className="relative ">
                                    <h1>الهاتف الاساسى</h1>
                                    <input
                                        type="tel"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الهاتف الاساسى"
                                        value={phone}
                                        onChange={(e) =>
                                            setPhone(e.target.value)
                                        }
                                    />
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
                                    <h1>التليفون الثالث</h1>
                                    <input
                                        type="tel"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="التليفون الثالث"
                                        value={phone3}
                                        onChange={(e) =>
                                            setPhone3(e.target.value)
                                        }
                                    />
                                </div>

                                <div className="relative ">
                                    <h1>التليفون الرابع</h1>
                                    <input
                                        type="tel"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="التليفون الرابع"
                                        value={phone4}
                                        onChange={(e) =>
                                            setPhone4(e.target.value)
                                        }
                                    />
                                </div>

                                <div className="relative ">
                                    <h1>التليفون الخامس</h1>
                                    <input
                                        type="tel"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="التليفون الخامس"
                                        value={phone5}
                                        onChange={(e) =>
                                            setPhone5(e.target.value)
                                        }
                                    />
                                </div>

                                <div className="relative ">
                                    <h1>الايميل</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الايميل"
                                        value={email}
                                        onChange={(e) =>
                                            setEmail(e.target.value)
                                        }
                                    />
                                </div>

                                <div className="relative ">
                                    <h1>كود التاجر</h1>
                                    <input
                                        type="text"
                                        className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="الكود"
                                        value={traderCode}
                                        onChange={(e) =>
                                            setTraderCode(e.target.value)
                                        }
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
                            onClick={updateTraderFunc}
                        >
                            إضافة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default UpdateTrader;
