import axios from "axios";
import React, { useEffect, useState } from "react";

const AddUnit = ({ fetchAgainFunc, togglAddModal, levelInfo }) => {
    const [unitName, setUnitName] = useState("");
    const [unitSpace, setUnitSpace] = useState("");
    const [meterPrice, setMeterPrice] = useState("");
    const [unitPrice, setUnitPrice] = useState("");
    const [unitPriceVal, setUnitPriceVal] = useState("");
    const [unitDescription, setUnitDescription] = useState("");

    const [sucessMsg, setSuccessMsg] = useState("");

    console.log(levelInfo);

    const [sites, setSites] = useState([]);

    const [levelID, setLevelID] = useState("");
    const [projectId, setprojectId] = useState("");
    const [siteID, setSiteID] = useState("");

    useEffect(() => {
        const { id, project_id } = levelInfo;
        setLevelID(id);
        setprojectId(project_id);
    }, []);

    // --------------------- Validation inputs -------------------------------
    const [unitNameValid, setUnitNameValid] = useState(false);
    const [unitSpaseValid, setUnitSpaceValid] = useState(false);
    const [meterPriceValid, setMeterPriceValid] = useState(false);

    //----------------------- validation inputs check -----------------------
    useEffect(() => {
        unitName == "" ? setUnitNameValid(true) : setUnitNameValid(false);

        unitSpace == "" ? setUnitSpaceValid(true) : setUnitSpaceValid(false);

        meterPrice == "" ? setMeterPriceValid(true) : setMeterPriceValid(false);
    }, [unitName, unitSpace, meterPrice]);
    //----------------------- validation inputs check -----------------------

    useEffect(() => {
        let calcUnitValue = unitSpace * meterPrice;
        setUnitPriceVal(calcUnitValue);
    }, [unitSpace, meterPrice]);

    ////////////////////////////////////////////////////// End Selections //////////////////////////////////////////////////////////////////////
    const emptyValues = () => {
        setUnitName("");
        setUnitSpace("");
        setMeterPrice("");
        setUnitPriceVal("");
        setUnitDescription("");
        setLevelID("");
        setprojectId("");
    };
    //////////////////////////////////////////////////////// Add New Unit Function  ///////////////////////////////////////////////////////////
    console.log(levelID);
    const addNewUnit = async () => {
        let validnumber = /[0-9]/;
        if (
            unitName != "" &&
            unitSpace.match(validnumber) != null &&
            meterPrice.match(validnumber) != null &&
            projectId != "" &&
            levelID != ""
        ) {
            try {
                await axios
                    .post(`${process.env.MIX_APP_URL}/api/units`, {
                        name: unitName,
                        level_id: levelInfo.id, // select الطوابق (الادوار) hidden
                        site_id: siteID, // select --> المواقع قبلى او بحرى
                        space: unitSpace,
                        price_m: meterPrice,
                        unit_value: unitPriceVal,
                        description: unitDescription,
                    })
                    .then((res) => {
                        console.log(res.data.message);
                        setSuccessMsg(res.data.message);
                        emptyValues();
                        fetchAgainFunc();
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 3000);
                    });
            } catch (err) {
                console.log(err);
            }
        } else {
            console.log("not valid");
        }
    };

    return (
        <div
            id="defaultModal"
            tabIndex="-1"
            className="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 bottom-0 z-50 w-full md:inset-0 h-screen justify-center items-center flex bg-slate-800/75"
            aria-modal="true"
            role="dialog"
            dir="rtl"
        >
            {sucessMsg.length > 0 && (
                <div className="fixed top-32 z-50 p-2 text-white text-center w-full left-0 bg-green-500">
                    {sucessMsg}
                </div>
            )}
            <div className="relative p-4 w-full max-w-4xl h-full md:h-auto">
                <div className="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div className="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                        <h3 className="text-xl font-semibold text-gray-900 dark:text-white">
                            إضافة وحدة جديدة
                        </h3>
                        <button
                            onClick={togglAddModal}
                            type="button"
                            className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        >
                            <svg
                                aria-hidden="true"
                                className="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fillRule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clipRule="evenodd"
                                ></path>
                            </svg>
                            <span className="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div className="p-6 space-y-6">
                        <form className="flex flex-col items-center gap-3 mt-6">
                            <div className="flex flex-wrap justify-between gap-y-5 gap-4 w-full">
                                <div className="relative pb-3 min-w-full">
                                    <small>اسم الوحدة</small>

                                    <input
                                        onChange={(e) =>
                                            setUnitName(e.target.value)
                                        }
                                        value={unitName}
                                        type="text"
                                        className="py-1 min-w-full px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="اسم الوحدة"
                                    />
                                    {unitNameValid && (
                                        <span className="text-sm text-red-500 absolute -bottom-3 right-0">
                                            إسم الوحدة مطلوب
                                        </span>
                                    )}
                                </div>

                                <div className="rest-inputs flex gap-4 flex-wrap">
                                    <div className="relative pb-3">
                                        <small>مساحة الوحدة</small>
                                        <input
                                            onChange={(e) =>
                                                setUnitSpace(e.target.value)
                                            }
                                            value={unitSpace}
                                            type="number"
                                            className="py-1 min-w-full px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                            placeholder="مساحة الوحدة"
                                        />
                                        {unitSpaseValid && (
                                            <span className="text-sm text-red-500 absolute -bottom-3 right-0">
                                                مساحة الوحدة مطلوبة
                                            </span>
                                        )}
                                    </div>

                                    <div className="relative pb-3">
                                        <small>سعر المتر</small>
                                        <input
                                            onChange={(e) =>
                                                setMeterPrice(e.target.value)
                                            }
                                            value={meterPrice}
                                            type="number"
                                            className="py-1 min-w-full px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                            placeholder="سعر المتر"
                                        />
                                        {meterPriceValid && (
                                            <span className="text-sm text-red-500 absolute -bottom-3 right-0">
                                                سعر المتر مطلوب
                                            </span>
                                        )}
                                    </div>

                                    <div className="relative pb-3">
                                        <small>سعر الوحدة</small>
                                        <input
                                            onChange={(e) =>
                                                setUnitPriceVal(e.target.value)
                                            }
                                            value={unitPriceVal}
                                            type="number"
                                            className="py-1 min-w-full px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                            placeholder="سعر الوحدة"
                                        />
                                    </div>
                                </div>
                                <div className="relative pb-3">
                                    <textarea
                                        onChange={(e) =>
                                            setUnitDescription(e.target.value)
                                        }
                                        value={unitDescription}
                                        type="text"
                                        rows="4"
                                        cols="50"
                                        className="py-1  px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                        placeholder="وصف الوحدة"
                                    ></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div className="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                        <button
                            onClick={togglAddModal}
                            data-modal-toggle="defaultModal"
                            type="button"
                            className="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 ml-2"
                        >
                            إلغاء
                        </button>
                        <button
                            onClick={addNewUnit}
                            data-modal-toggle="defaultModal"
                            type="button"
                            className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            إضافة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AddUnit;
