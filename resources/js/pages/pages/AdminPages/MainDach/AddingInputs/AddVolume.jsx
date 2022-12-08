import axios from "axios";
import React, { useState } from "react";

const AddVolume = () => {
    const [isAddValume, setIsAddValume] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // -----------------------| (Sizes) |-------------------- \\
    const [volumeName, setVolumeName] = useState("");
    const addVolume = async () => {
        if (volumeName.length > 0) {
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/volumes`, {
                        name: volumeName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setVolumeName("");
                        setIsAddValume(!isAddValume);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 5000);
                    });
            } catch (er) {
                console.log(er);
            }
        } else {
            console.log("not valid");
        }
    };
    // -----------------------| (Sizes) |-------------------- \\

    return (
        <div className="colors-div relative mt-4 border-2 p-2">
            <h1>إضافة الحجم</h1>
            {successMsg.length > 0 && (
                <div className="msg absolute top-0 left-0 w-full text-start rounded-md p-2 bg-green-400">
                    <span>{successMsg}</span>
                </div>
            )}
            <input
                type="text"
                value={volumeName}
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setVolumeName(e.target.value)}
                placeholder="اضف الحجم"
            />
            {!isAddValume ? (
                <button
                    onClick={() => setIsAddValume(!isAddValume)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة الحجم
                </button>
            ) : (
                <span>
                    <button
                        onClick={addVolume}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة الحجم
                    </button>
                    <button
                        onClick={() => setIsAddValume(!isAddValume)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddVolume;
