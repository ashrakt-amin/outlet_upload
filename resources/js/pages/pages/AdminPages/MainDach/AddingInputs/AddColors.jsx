import axios from "axios";
import React, { useState } from "react";

const AddColors = () => {
    const [isAddColor, setIsAddColor] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // -----------------------| (colors) |-------------------- \\
    const [colorName, setColorName] = useState("");
    const addColor = async () => {
        if (colorName.length > 0) {
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/colors`, {
                        name: colorName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setColorName("");
                        setIsAddColor(!isAddColor);
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
        <div className="colors-div relative mt-4 border-2 p-2">
            <h1>إضافة لون</h1>
            {successMsg.length > 0 && (
                <div className="msg absolute top-0 left-0 w-full text-start rounded-md p-2 bg-green-400">
                    <span>{successMsg}</span>
                </div>
            )}
            <input
                type="text"
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setColorName(e.target.value)}
                value={colorName}
                placeholder="اضف لون"
            />
            {!isAddColor ? (
                <button
                    onClick={() => setIsAddColor(!isAddColor)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة اللون
                </button>
            ) : (
                <span className="">
                    <button
                        onClick={addColor}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة اللون
                    </button>
                    <button
                        onClick={() => setIsAddColor(!isAddColor)}
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
