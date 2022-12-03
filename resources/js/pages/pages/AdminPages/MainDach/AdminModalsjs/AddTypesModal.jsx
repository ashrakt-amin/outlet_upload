import axios from "axios";
import React, { useState } from "react";

import InputLabel from "@mui/material/InputLabel";
import MenuItem from "@mui/material/MenuItem";
import FormControl from "@mui/material/FormControl";
import Select from "@mui/material/Select";
import { useEffect } from "react";

function AddTypesModal({ togleTypes }) {
    const [typesName, setTypesName] = useState("");
    const [groupId, setGroupId] = useState("");

    const [successMsg, setSuccessMsg] = useState("");

    const [groupIdName, setGroupIdName] = useState("");
    const [groupsArr, setGroupsArr] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getSubCategories = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/groups`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setGroupsArr(res.data.data);
            } catch (er) {
                console.log(er);
            }
        };
        getSubCategories();
    }, []);

    const addTypes = async () => {
        if (typesName.length > 0 && groupId != "") {
            try {
                axios
                    .post(`http://127.0.0.1:8000/api/types`, {
                        name: typesName,
                        group_id: groupId,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setTypesName("");
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

    const handleSubCategId = (group) => {
        setGroupId(group.id);
        setGroupIdName(group.name);
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
            {successMsg.length > 0 && (
                <div className="msg fixed top-20 left-0 w-full rounded-md p-2 bg-green-400">
                    {successMsg}
                </div>
            )}

            <div className="relative p-4 w-full max-w-4xl h-full md:h-auto">
                <div className="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div className="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                        <h3 className="text-xl font-semibold text-gray-900 dark:text-white">
                            داخل المجموعة
                        </h3>

                        <button
                            type="button"
                            className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            onClick={() => togleTypes()}
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
                        <form className="flex flex-col items-start gap-3 mt-6 dark:text-black relative">
                            <span>اسم داخل المجموعة</span>
                            <div className="grid grid-cols-1 md:grid-cols-2 w-full gap-2">
                                <input
                                    type="text"
                                    className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                                    placeholder="مثال : بنطلونات-سامسونج Note"
                                    value={typesName}
                                    onChange={(e) =>
                                        setTypesName(e.target.value)
                                    }
                                />
                            </div>
                            <FormControl
                                className="form-select"
                                sx={{ m: 1, minWidth: 160 }}
                                size="small"
                            >
                                <InputLabel id="demo-select-small">
                                    {" "}
                                    التصنيفات داخل الفرعية
                                </InputLabel>
                                <Select
                                    labelId="demo-select-small"
                                    id="demo-select-small"
                                    value={groupIdName}
                                    label="التصنيفات الفرعية"
                                >
                                    {groupsArr &&
                                        groupsArr.map((group) => (
                                            <MenuItem
                                                key={group.id}
                                                onClick={() =>
                                                    handleSubCategId(group)
                                                }
                                                value={group.name}
                                            >
                                                {group.name}
                                            </MenuItem>
                                        ))}
                                </Select>
                            </FormControl>
                        </form>
                    </div>
                    <div className="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                        <button
                            data-modal-toggle="defaultModal"
                            type="button"
                            className="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 ml-2"
                            onClick={togleTypes}
                        >
                            إلغاء
                        </button>
                        <button
                            onClick={addTypes}
                            data-modal-toggle="defaultModal"
                            type="button"
                            className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            إضافة داخل المجموعة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default AddTypesModal;
