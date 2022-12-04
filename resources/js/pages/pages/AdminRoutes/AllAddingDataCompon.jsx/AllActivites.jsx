import axios from "axios";
import React, { useEffect, useState } from "react";

const AllActivites = () => {
    const [activites, setActivites] = useState([]);

    const [acivityName, setAcivityName] = useState("");
    const [activityId, setActivityId] = useState("");
    const [deleteActivityName, setDeleteActivityName] = useState("");

    const [successMsg, setSuccessMsg] = useState("");

    const [isActivity, setIsActivity] = useState(false);
    const [isDelete, setIsDelete] = useState(false);

    const [fetchAgain, setFechAgain] = useState(false);

    useEffect(() => {
        let adminTrue = JSON.parse(localStorage.getItem("uTk"));
        const cancelRequest = axios.CancelToken.source();
        const getActivites = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/activities`
                    // {
                    //     headers: {
                    //         Authorization: `Bearer ${adminTrue}`,
                    //     },
                    // }
                    // {
                    //     cancelRequest: cancelRequest.token,
                    // }
                );
                setActivites(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getActivites();
    }, [fetchAgain]);

    const addjust = (activ) => {
        setIsActivity(true);
        setAcivityName(activ.name);
        setActivityId(activ.id);
        setIsDelete(false);
    };

    const adjustNow = async () => {
        try {
            const res = await axios.put(
                `${process.env.MIX_APP_URL}/api/activities/${activityId}`,
                { name: acivityName }
            );
            setFechAgain(!fetchAgain);
            setIsActivity(false);
            setAcivityName("");
            setSuccessMsg(res.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 3000);
        } catch (er) {
            console.log(er);
        }
    };

    const deleteActivity = (activ) => {
        setActivityId(activ.id);
        setDeleteActivityName(activ.name);
        setIsDelete(true);
        setIsActivity(false);
    };

    const deleteNowFunc = async () => {
        try {
            const res = await axios.delete(
                `${process.env.MIX_APP_URL}/api/activities/${activityId}`
            );
            setFechAgain(!fetchAgain);
            setIsActivity(false);
            setIsDelete(false);
            setSuccessMsg(res.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 3000);
        } catch (er) {
            console.log(er);
        }
    };

    return (
        <div dir="rtl" className="p-3 border-4 my-4 mx-2 rounded-md">
            <h1>النشاطات</h1>
            {successMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-red-500">
                    {successMsg}
                </div>
            )}
            <details>
                <summary className="cursor-pointer">إظهار النشاطات</summary>

                <div>
                    {isActivity && (
                        <div>
                            <input
                                onChange={(e) => setAcivityName(e.target.value)}
                                type="text"
                                className="rounded-md mx-1"
                                value={acivityName}
                            />
                            <button
                                onClick={adjustNow}
                                className="bg-green-500 mx-1 rounded-md p-2 text-white"
                            >
                                تأكيد التعديل
                            </button>

                            <button
                                onClick={() => setIsActivity(!isActivity)}
                                className="bg-red-600 mx-1 rounded-md p-2 text-white"
                            >
                                إلغاء
                            </button>
                        </div>
                    )}
                    {isDelete && (
                        <div className="flex items-center gap-3 shadow-md my-3 p-2">
                            <h1 className="text-2xl">{deleteActivityName}</h1>
                            <button
                                onClick={() => deleteNowFunc()}
                                className="bg-red-600 mx-1 text-sm rounded-md p-1 text-white"
                            >
                                تأكيد حذف النشاط
                            </button>
                            <button
                                onClick={() => setIsDelete(!isDelete)}
                                className="bg-red-700 mx-1 text-sm rounded-md p-1 text-white"
                            >
                                إلغاء الحذف
                            </button>
                        </div>
                    )}
                    {activites &&
                        activites.map((active) => (
                            <div
                                key={active.id}
                                className="flex gap-3 my-3 mt-5 p-2"
                            >
                                <h3>{active.name}</h3>
                                <button
                                    className="text-yellow-400 border-2 shadow-sm hover:bg-yellow-300 px-2 rounded-md hover:text-white transition-all duration-900 ease-in-out"
                                    onClick={() => addjust(active)}
                                >
                                    تعديل
                                </button>
                                <button
                                    className="text-red-500 border-2 shadow-sm hover:bg-red-600 px-3 hover:text-white rounded-md transition-all duration-900 ease-in-out"
                                    onClick={() => deleteActivity(active)}
                                >
                                    مسح
                                </button>
                            </div>
                        ))}
                </div>
            </details>
        </div>
    );
};

export default AllActivites;
