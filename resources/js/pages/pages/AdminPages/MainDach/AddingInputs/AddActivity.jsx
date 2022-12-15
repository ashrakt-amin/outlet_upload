import axios from "axios";
import React, { useEffect, useState } from "react";

const AddActivity = () => {
    const [isAddActivity, setIsAddActivity] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // -----------------------| (colors) |-------------------- \\
    const [activityName, setActivityName] = useState("");
    const addColor = async () => {
        if (activityName.length > 0) {
            setIsAddActivity(!isAddActivity);
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/activities`, {
                        name: activityName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setIsAddActivity(!isAddActivity);
                        setActivityName("");
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 2000);
                    });
            } catch (er) {
                console.log(er);
            }
        }
    };
    // -----------------------| (colors) |-------------------- \\

    return (
        <div className="colors-div relative mt-4 border-2 p-2">
            <h1>إضافة نشاط</h1>
            {successMsg.length > 0 && (
                <div className="msg absolute top-0 left-0 w-full text-start rounded-md p-2 bg-green-400">
                    <span>{successMsg}</span>
                </div>
            )}
            <input
                type="text"
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setActivityName(e.target.value)}
                value={activityName}
                placeholder="اضف نشاط"
            />
            {!isAddActivity ? (
                <button
                    onClick={() => setIsAddActivity(!isAddActivity)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة النشاط
                </button>
            ) : (
                <span className="">
                    <button
                        onClick={addColor}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة النشاط
                    </button>
                    <button
                        onClick={() => setIsAddActivity(!isAddActivity)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddActivity;
