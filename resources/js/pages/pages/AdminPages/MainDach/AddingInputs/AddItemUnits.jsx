import axios from "axios";
import React, { useState } from "react";

const AddColors = () => {
    const [isAddItemUnits, setIsAddItemUnits] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // -----------------------| (colors) |-------------------- \\
    const [itemUnitName, setItemUnitName] = useState("");
    const addItemUnits = async () => {
        const getToken = JSON.parse(localStorage.getItem("uTk"));
        if (itemUnitName.length > 0) {
            try {
                axios
                    .post(
                        `${process.env.MIX_APP_URL}/api/itemUnits`,
                        {
                            name: itemUnitName,
                        },
                        // {
                        //     headers: {
                        //         Authorization: `Bearer ${getToken}`,
                        //     },
                        // }
                    )
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setIsAddItemUnits(!isAddItemUnits);
                        setItemUnitName("");
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
            <h1>اضافة وحدة للمنتج</h1>
            {successMsg.length > 0 && (
                <div className="msg fixed top-20 left-0 w-full rounded-md p-2 bg-green-400">
                    {successMsg}
                </div>
            )}
            <input
                type="text"
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setItemUnitName(e.target.value)}
                value={itemUnitName}
                placeholder="اضف وحدة للمنتج"
            />
            {!isAddItemUnits ? (
                <button
                    onClick={() => setIsAddItemUnits(!isAddItemUnits)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة وحدة للمنتج
                </button>
            ) : (
                <span>
                    <button
                        onClick={addItemUnits}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة وحدة للمنتج
                    </button>
                    <button
                        onClick={() => setIsAddItemUnits(!isAddItemUnits)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddColors;
