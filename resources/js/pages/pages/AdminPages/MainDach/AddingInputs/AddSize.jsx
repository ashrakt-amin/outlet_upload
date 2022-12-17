import axios from "axios";
import React, { useState } from "react";

const AddSize = () => {
    const [isAddSize, setIsAddSize] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // -----------------------| (Sizes) |-------------------- \\
    const [sizeName, setSizeName] = useState("");
    const addSize = async () => {
        if (sizeName.length > 0) {
            setIsAddSize(!isAddSize);
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/sizes`, {
                        name: sizeName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setSizeName("");
                        setIsAddSize(!isAddSize);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 2000);
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
            <h1>إضافة مقاس</h1>
            {successMsg.length > 0 && (
                <div className="msg absolute top-0 left-0 w-full text-start rounded-md p-2 bg-green-400">
                    <span>{successMsg}</span>
                </div>
            )}
            <input
                type="text"
                value={sizeName}
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setSizeName(e.target.value)}
                placeholder="اضف مقاس"
            />
            {!isAddSize ? (
                <button
                    onClick={() => setIsAddSize(!isAddSize)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة مقاس
                </button>
            ) : (
                <span>
                    <button
                        onClick={addSize}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة مقاس
                    </button>
                    <button
                        onClick={() => setIsAddSize(!isAddSize)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddSize;
