import axios from "axios";
import React, { useState } from "react";

const OneAdvertisement = ({ advertise, refetch }) => {
    const [imgVal, setImgVal] = useState(null);

    const [renewNum, setRenewNum] = useState("");

    const [successMsg, setsuccessMsg] = useState("");

    const [linkName, setLinkName] = useState("");

    const [isUpdateLink, setIsUpdateLink] = useState(false);

    const [isImgUpdate, setIsImgUpdate] = useState(false);

    const [isRemaining, setIsRemaining] = useState(false);

    const handleImg = (e) => {
        setIsImgUpdate(true);
        setImgVal(e.target.files[0]);
    };

    const showLinkInput = (link) => {
        setIsUpdateLink(true);
        setLinkName(link);
    };

    const updateLink = async (adv) => {
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        try {
            let res = await axios.put(
                `http://127.0.0.1:8000/api/advertisements/${adv.id}`,
                {
                    link: linkName,
                },
                {
                    headers: { Authorization: `Bearer ${getToken}` },
                }
            );
            setIsUpdateLink(!isUpdateLink);
            setsuccessMsg(res.data.message);
            setTimeout(() => {
                setsuccessMsg("");
            }, 3000);
            refetch();
        } catch (er) {
            console.log(er);
            setIsUpdateLink(false);
        }
    };

    const updateImg = async (imgvalue) => {
        const getToken = JSON.parse(localStorage.getItem("uTk"));
        const fData = new FormData();
        fData.append("img", imgVal);
        try {
            let res = await axios.put(
                `http://127.0.0.1:8000/api/advertisements/${imgvalue.id}`,
                { img: imgVal }
                // {
                //     headers: { Authorization: `Bearer ${getToken}` },
                // }
            );
            console.log(res);
            refetch();
        } catch (er) {
            console.log(er);
            setIsUpdateLink(false);
        }
    };

    const updateRemainginTime = async (remainingVal) => {
        try {
            let res = await axios.put(
                `http://127.0.0.1:8000/api/advertisements/${remainingVal.id}`,
                { renew: renewNum }
            );
            setsuccessMsg(res.data.message);
            setIsRemaining(!isRemaining)
            setTimeout(() => {
                setsuccessMsg("");
            }, 3000);
            refetch();
        } catch (er) {
            console.log(er);
            setIsUpdateLink(false);
        }
    };

    return (
        <div className="img-advertise-div rounded-md p-3 m-3 bg-green-300">
            {successMsg && <h1>{successMsg}</h1>}

            <h1 className="text-center text-lg">{advertise.id}</h1>
            <div
                className="img-advertise-container"
                style={{ maxWidth: "100%" }}
            >
                <img
                    src={`http://127.0.0.1:8000/assets/images/uploads/advertisements/${advertise.img}`}
                    alt=""
                />
            </div>

            <div className="trader-name shadow-md p-1 rounded-md m-2 bg-slate-300">
                اسم التاجر :{" "}
                {`${advertise.traders.f_name} ${advertise.traders.m_name} ${advertise.traders.l_name}`}
            </div>

            <div className="advertise-link-show w-fit shadow-md p-1 rounded-md m-2 bg-slate-300">
                <span>لينك الاعلان :</span>{" "}
                <span style={{ wordBreak: "break-all" }}>{advertise.link}</span>
            </div>

            <div className="daysRemainig shadow-md p-1 rounded-md m-2 bg-slate-300">
                مدة المتبقية للاعلان : {`${advertise.daysRemainig}`} يوم
            </div>

            <div className="update-remaining-time">
                {isRemaining && (
                    <div>
                        <h3>مدة الاعلان الجديدة</h3>
                        <input
                            className="py-2 px-3 border-2 border-slate-200 rounded-lg outline-none font-serif"
                            type="number"
                            value={renewNum}
                            min={0}
                            onChange={(e) => setRenewNum(e.target.value)}
                        />
                        <button
                            className="bg-green-500 rounded-md p-2 mb-3 text-white"
                            onClick={() => updateRemainginTime(advertise)}
                        >
                            تأكيد تعديل المدة
                        </button>
                        <button
                            className="bg-red-500 mx-2 rounded-md p-2 mb-3 text-white"
                            onClick={() => setIsRemaining(false)}
                        >
                            الغاء
                        </button>
                    </div>
                )}

                {!isRemaining && (
                    <button
                        className="bg-green-500 rounded-md p-2 mb-3 text-white"
                        onClick={() => setIsRemaining(!isRemaining)}
                    >
                        تعديل المدة
                    </button>
                )}
            </div>

            <div className="adjust-img-advertise bg-slate-200 m-3 p-2 rounded-md">
                <h1>تعديل صورة الاعلان</h1>
                <input type="file" onChange={handleImg} />
            </div>

            {isImgUpdate && (
                <button
                    className="bg-green-500 rounded-md p-2 mb-3 text-white"
                    onClick={() => updateImg(advertise)}
                >
                    تأكيد تعديل الصورة
                </button>
            )}

            {isUpdateLink && (
                <div className="link-input-div">
                    <h1> لينك الاعلان الجديد</h1>
                    <input
                        onChange={(e) => setLinkName(e.target.value)}
                        className="py-2 px-3 border-2 border-slate-200 rounded-lg outline-none font-serif w-full"
                        type="text"
                        value={linkName}
                    />
                    <button
                        className="bg-red-500 rounded-md p-2 mb-3 text-white"
                        onClick={() => setIsUpdateLink(!isUpdateLink)}
                    >
                        الغاء
                    </button>
                </div>
            )}

            <div className="update-advertise-link">
                {!isUpdateLink ? (
                    <button
                        className="bg-green-500 rounded-md p-2 my-3 text-white"
                        onClick={() => showLinkInput(advertise.link)}
                    >
                        تعديل اللينك
                    </button>
                ) : (
                    <button
                        className="bg-green-500 rounded-md p-2 my-3 text-white"
                        onClick={() => updateLink(advertise)}
                    >
                        تأكيد تعديل اللينك
                    </button>
                )}
            </div>
        </div>
    );
};

export default OneAdvertisement;
