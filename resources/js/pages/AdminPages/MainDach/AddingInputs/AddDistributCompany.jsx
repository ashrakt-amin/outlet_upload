import axios from "axios";
import React, { useState } from "react";

const AddDistributCompany = () => {
    const [isDistributCompany, setIsDistributCompany] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    const [distributCompanyName, setDistributCompanyName] = useState("");

    // (----------------------- (addDistributCompany (companies) Function) -----------------------)
    const addDistributCompany = async () => {
        if (distributCompanyName.length > 0) {
            try {
                axios
                    .post(`http://127.0.0.1:8000/api/companies`, {
                        name: distributCompanyName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setDistributCompanyName("");
                        setIsDistributCompany(!isDistributCompany);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 5000);
                    });
            } catch (er) {
                console.log(er);
            }
        } else {
            console.log("not vaild");
        }
    };
    // (----------------------- (addDistributCompany (companies) Function) -----------------------)
    return (
        <div className="companies-div mt-4 border-2 p-2">
            <h1>الشركة الموزعة </h1>
            {successMsg.length > 0 && (
                <div className="msg fixed top-20 left-0 w-full rounded-md p-2 bg-green-400">
                    {successMsg}
                </div>
            )}
            <input
                type="text"
                value={distributCompanyName}
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setDistributCompanyName(e.target.value)}
                placeholder="الشركة الموزعة "
            />
            {!isDistributCompany ? (
                <button
                    onClick={() => setIsDistributCompany(!isDistributCompany)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة الشركة الموزعة
                </button>
            ) : (
                <span>
                    <button
                        onClick={addDistributCompany}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة الشركة الموزعة
                    </button>
                    <button
                        onClick={() =>
                            setIsDistributCompany(!isDistributCompany)
                        }
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddDistributCompany;
