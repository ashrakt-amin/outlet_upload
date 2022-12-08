import axios from "axios";
import React, { useState } from "react";

const AddManufactories = () => {
    const [manufactorCompaneyName, setManufactorCompaneyName] = useState("");

    const [isAddManifec, setIsAddManifec] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // (----------------------- (manufactor Function) -----------------------)
    const addManufactorComp = async () => {
        if (manufactorCompaneyName.length > 0) {
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/manufactories`, {
                        name: manufactorCompaneyName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setManufactorCompaneyName("");
                        setIsAddManifec(!isAddManifec);
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
    // (----------------------- (manufactor Function) -----------------------)
    return (
        <div className="manufactories-div relative mt-4 border-2 p-2">
            <h1>الشركة المصنعة </h1>
            {successMsg.length > 0 && (
                <div className="msg absolute top-0 left-0 w-full text-start rounded-md p-2 bg-green-400">
                    <span>{successMsg}</span>
                </div>
            )}
            <input
                type="text"
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setManufactorCompaneyName(e.target.value)}
                placeholder="الشركة المصنعة "
            />

            {!isAddManifec ? (
                <button
                    onClick={() => setIsAddManifec(!isAddManifec)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة الشركة المصنعة
                </button>
            ) : (
                <span>
                    <button
                        onClick={addManufactorComp}
                        className="bg-blue-600 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة الشركة المصنعة
                    </button>
                    <button
                        onClick={() => setIsAddManifec(!isAddManifec)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddManufactories;
