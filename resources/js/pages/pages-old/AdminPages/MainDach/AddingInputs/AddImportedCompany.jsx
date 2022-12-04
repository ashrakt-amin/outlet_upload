import axios from "axios";
import React, { useState } from "react";

const AddImportedCompany = () => {
    const [isImportedCompany, setImportedCompany] = useState(false);

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    const [importersName, setImportersName] = useState("");
    // (----------------------- (Importer Companey Function) -----------------------)
    const addImporterCompaney = async () => {
        if (importersName.length > 0) {
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/importers`, {
                        name: importersName,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setImportersName("");
                        setImportedCompany(!isImportedCompany);
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
    // (----------------------- (Importer Companey Function) -----------------------)
    return (
        <div className="companies-div mt-4 border-2 p-2">
            <h1>الشركة المستوردة </h1>
            {successMsg.length > 0 && (
                <div className="msg fixed top-20 left-0 w-full rounded-md p-2 bg-green-400">
                    {successMsg}
                </div>
            )}
            <input
                value={importersName}
                type="text"
                className="border-none rounded-lg shadow-md my-3 mx-4"
                onChange={(e) => setImportersName(e.target.value)}
                placeholder="الشركة المستوردة "
            />

            {!isImportedCompany ? (
                <button
                    onClick={() => setImportedCompany(!isImportedCompany)}
                    className="bg-blue-600 rounded-md p-2 text-white"
                >
                    إضافة الشركة المستوردة
                </button>
            ) : (
                <span>
                    <button
                        onClick={addImporterCompaney}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        تأكيد إضافة الشركة المستوردة
                    </button>
                    <button
                        onClick={() => setImportedCompany(!isImportedCompany)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddImportedCompany;
