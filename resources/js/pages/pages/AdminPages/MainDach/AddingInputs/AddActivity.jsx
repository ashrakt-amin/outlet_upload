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
            try {
                axios
                    .post(`http://127.0.0.1:8000/api/activities`, {
                        name: activityName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setIsAddActivity("");
                        setActivityName("");
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 5000);
                    });
            } catch (er) {
                console.log(er);
            }
        }
    };
    // -----------------------| (colors) |-------------------- \\

    return (
        <div className="colors-div mt-4 border-2 p-2">
            <h1>إضافة نشاط</h1>
            {successMsg.length > 0 && (
                <div className="msg fixed top-20 left-0 w-full rounded-md p-2 bg-green-400">
                    {successMsg}
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
