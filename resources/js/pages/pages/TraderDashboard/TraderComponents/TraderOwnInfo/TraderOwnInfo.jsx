import axios from "axios";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

const TraderOwnInfo = () => {
    const [traderInfo, setTraderInfo] = useState({});

    const [isUpdateTrader, setIsUpdateTrader] = useState(false);

    const [wrongInput, setWrongInput] = useState("");

    const [isUpdateImg, setIsUpdateImg] = useState(false);

    const [nameVal, setNameVal] = useState("");

    const [emailName, setEmailName] = useState("");
    const [fName, setFName] = useState("");
    const [mName, setMName] = useState("");
    const [lName, setLName] = useState("");

    const [imgVal, setImgVal] = useState(null);

    const [isName, setIsName] = useState(false);

    const [getData, setGetData] = useState(false);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        if (localStorage.getItem("trTk")) {
            let traderTk = JSON.parse(localStorage.getItem("trTk"));      

            const getTraders = async () => {
                try {
                    axios.defaults.withCredentials = true;
                    axios
                        .get(
                            `${process.env.MIX_APP_URL}/` +
                                "sanctum/csrf-cookie"
                        )
                        .then(async (res1) => {
                            let res = await axios.get(
                                `${process.env.MIX_APP_URL}/api/traders/trader`,
                                {
                                    headers: {
                                        Authorization: `Bearer ${traderTk}`,
                                    },
                                }
                            );
                            setTraderInfo(res.data.data[0]);
                            console.log(res.data);
                        });
                } catch (er) {
                    console.log(er);
                }
            };
            getTraders();
        } else {
            navig("/traderLogin");
        }

        return () => {
            cancelRequest.cancel();
        };
    }, [getData]);

    const updateFirstName = async (info) => {
        if (fName.length > 3) {
            let res = await axios.put(
                `${process.env.MIX_APP_URL}/api/traders/${info.id}`,
                { f_name: fName }
            );
            setGetData(!getData);
            setFName("");
        }
    };

    const updateMiddleName = async (info) => {
        if (mName.length > 3) {
            let res = await axios.put(
                `${process.env.MIX_APP_URL}/api/traders/${info.id}`,
                { m_name: mName }
            );
            setMName("");
            setGetData(!getData);
        }
    };

    const updateLastName = async (info) => {
        if (lName.length > 3) {
            let res = await axios.put(
                `${process.env.MIX_APP_URL}/api/traders/${info.id}`,
                { l_name: lName }
            );
            setLName("");
            setGetData(!getData);
        }
    };

    const updateEmail = async (info) => {
        let regEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (regEmail.test(emailName)) {
            console.log("valid");
            let res = await axios.put(
                `${process.env.MIX_APP_URL}/api/traders/${info.id}`,
                { email: emailName }
            );
            console.log(res);
            setEmailName("");
            setGetData(!getData);
        } else {
            console.log("not valid");
            setWrongInput("تاكد من كتابة الايميل بشكل صحيح");
            setTimeout(() => {
                setWrongInput("");
            }, 3000);
        }
    };

    const openUpdateTrader = () => {
        setIsUpdateTrader(true);
    };

    const handleImg = (e) => {
        setImgVal(e.target.files[0]);
        setIsUpdateImg(true);
    };

    console.log(imgVal);

    const updateImage = async (info) => {
        const fromData = new FormData();
        fromData.append("logo", imgVal);
        let res = await axios.put(
            `${process.env.MIX_APP_URL}/api/traders/${info.id}`,
            { logo: imgVal.name, f_name: "تعديل التاجر والصورة" }
        );
        console.log(res);
        setGetData(!getData);
    };

    return (
        <div className="trader-info-div">
            <div
                className="logo-div-trader-info my-3"
                style={{ maxWidth: "300px" }}
            >
                <img
                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/traders/${traderInfo.logo}`}
                    alt=""
                />
                <h1>تعديل الصورة</h1>
                <div className="adjust-img">
                    <input type="file" onChange={(e) => handleImg(e)} />
                    {isUpdateImg && (
                        <button
                            onClick={() => updateImage(traderInfo)}
                            className="text-green-400 mx-2 my-2 border-2 hover:py-2 shadow-sm hover:bg-green-400 px-2 rounded-md hover:text-white transition-all duration-900 ease-in-out"
                        >
                            تأكيد تعديل الصورة
                        </button>
                    )}
                </div>
            </div>

            {isName && (
                <div className="adjust-names-div mt-2">
                    <h1 className="">تعديل الاسم {nameVariable}</h1>
                    <input
                        onChange={(e) => setNameVal(e.target.value)}
                        type="text"
                        className="rounded-md mx-1 my-2"
                        value={nameVal}
                    />
                    <button className="text-green-400 mx-2 border-2 hover:py-2 shadow-sm hover:bg-green-300 px-2 rounded-md hover:text-white transition-all duration-900 ease-in-out">
                        تأكيد تعديل
                    </button>
                    <button
                        onClick={() => setIsName(false)}
                        className="text-red-400 mx-2 border-2 hover:py-2 shadow-sm hover:bg-red-400 px-2 rounded-md hover:text-white transition-all duration-900 ease-in-out"
                    >
                        الغاء التعديل
                    </button>
                </div>
            )}

            <div className="f-name-div flex gap-2 items-start my-3">
                <h1 onClick={() => updateFirstName(traderInfo)}>
                    الاسم الاول: {traderInfo.f_name}
                </h1>
            </div>
            <div className="m-name-div flex gap-2 items-start my-3">
                <h1>الاسم الثانى: {traderInfo.m_name}</h1>
            </div>
            <div className="l-name-div flex gap-2 items-start my-3">
                <h1>الاسم الثالث: {traderInfo.l_name}</h1>
            </div>
            <div className="l-name-div flex gap-2 items-start my-3">
                <h1>رقم الهاتف : {traderInfo.phone}</h1>
            </div>

            <div className="email flex gap-2 items-start my-3">
                <h1>
                    الايميل:{" "}
                    {traderInfo?.email == null ? "لا يوجد" : traderInfo?.email}
                </h1>
            </div>

            {!isUpdateTrader && (
                <button
                    className="p-2 rounded-md bg-yellow-300"
                    onClick={openUpdateTrader}
                >
                    تعديل المعلومات
                </button>
            )}

            {isUpdateTrader && (
                <button
                    className="p-2 rounded-md bg-red-500 text-white"
                    onClick={() => setIsUpdateTrader(false)}
                >
                    {" "}
                    الغاء التعديل{" "}
                </button>
            )}

            {isUpdateTrader && (
                <div className="update-info relative flex gap-5 flex-col">
                    {wrongInput.length > 0 && (
                        <h1 className="absolute bg-red-500 p-1 rounded-md top-1">
                            {wrongInput}
                        </h1>
                    )}
                    <div className="first-name-update">
                        <h1>تعديل الاسم الاول</h1>
                        <input
                            onChange={(e) => setFName(e.target.value)}
                            type="text"
                            value={fName}
                        />
                        <button
                            onClick={() => updateFirstName(traderInfo)}
                            className="bg-green-500 p-1 rounded-md m-2 text-white"
                        >
                            عدل الان
                        </button>
                    </div>

                    <div className="first-name-update">
                        <h1>تعديل الاسم الثانى</h1>
                        <input
                            onChange={(e) => setMName(e.target.value)}
                            type="text"
                            value={mName}
                        />
                        <button
                            onClick={() => updateMiddleName(traderInfo)}
                            className="bg-green-500 p-1 rounded-md m-2 text-white"
                        >
                            عدل الان
                        </button>
                    </div>

                    <div className="first-name-update">
                        <h1>تعديل الاسم الثالث</h1>
                        <input
                            onChange={(e) => setLName(e.target.value)}
                            type="text"
                            value={lName}
                        />

                        <button
                            onClick={() => updateLastName(traderInfo)}
                            className="bg-green-500 p-1 rounded-md m-2 text-white"
                        >
                            عدل الان
                        </button>
                    </div>

                    <div className="first-name-update">
                        <h1>تعديل الايميل</h1>
                        <input
                            onChange={(e) => setEmailName(e.target.value)}
                            type="email"
                            value={emailName}
                        />
                        <button
                            onClick={() => updateEmail(traderInfo)}
                            className="bg-green-500 p-1 rounded-md m-2 text-white"
                        >
                            عدل الان
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default TraderOwnInfo;
