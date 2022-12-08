import React, { useEffect, useState } from "react";

const AddCategories = () => {
    const [categoryId, setCategoryId] = useState([]);

    const [isAddCategory, setIsAddCategory] = useState(false);

    const [categorryMsg, setCategorryMsg] = useState("");

    const [categorryVal, setCategorryVal] = useState("");

    const [categoryIdVal, setCategoryIdVal] = useState("");

    const [getCetgAgain, setgetCetgAgain] = useState(false);

    //--------------------------------------- ("Get All Categories") ---------------------------------------\\
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getCategorry = async () => {
            try {
                axios
                    .get(`${process.env.MIX_APP_URL}/api/categories`, {
                        name: categorryVal,
                    })
                    .then((res) => {
                        console.log(res.data.data);
                        setCategoryId(res.data.data);
                    });
            } catch (er) {
                console.log(er);
            }
        };
        getCategorry();

        return () => {
            cancelRequest.cancel();
        };
    }, [getCetgAgain]);
    //--------------------------------------- (" End Get All Categories") ---------------------------------------\\

    //--------------------------------------- ("Adding Category Function") ---------------------------------------\\
    const addCategorry = async () => {
        if (categorryVal.length > 0) {
            setIsAddCategory(!isAddCategory);
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/categories`, {
                        name: categorryVal,
                        category_id: categoryIdVal, //select
                    })
                    .then((res) => {
                        setCategorryMsg(`${res.data.data.name}`);
                        setIsAddCategory(!isAddCategory);
                        setgetCetgAgain(!getCetgAgain);
                        setCategorryVal("");
                        setTimeout(() => {
                            setCategorryMsg("");
                        }, 2000);
                    });
            } catch (er) {
                setCategorryMsg(er.response.data.message);
                setTimeout(() => {
                    setCategorryMsg("");
                }, 5000);
            }
        }
    };
    //--------------------------------------- ("Adding Category Function") ---------------------------------------\\

    const whatCategoryId = (categid) => {
        setCategoryIdVal(categid.target.value);
    };

    return (
        <div className="addCategorry-div mx-3 flex gap-3 relative items-center border-2 my-5 p-3 rounded-md">
            {categorryMsg.length > 0 && (
                <div
                    className="category-msg w-full p-1 text-start text-lg
                 text-white absolute top-0 left-0 z-10 "
                >
                    <span className="bg-green-400 rounded-sm">
                        {" تم إضافة"}
                        {categorryMsg}
                    </span>
                </div>
            )}
            <span>التصنيف الأساسى</span>
            <input
                type="text"
                value={categorryVal}
                placeholder="مثال : موضة - اجهزة إلكترونية"
                className="border-none rounded-lg shadow-md my-3"
                onChange={(e) => setCategorryVal(e.target.value)}
            />
            {isAddCategory && (
                <>
                    <select
                        className="rounded-md cursor-pointer"
                        onChange={whatCategoryId}
                        name="categoryId"
                        id="categoryId"
                        value={categoryIdVal}
                    >
                        <option value={"0"}>لم تختر بعد</option>
                        {categoryId &&
                            categoryId.map((oneCategId) => (
                                <option
                                    value={oneCategId.id}
                                    key={oneCategId.id}
                                >
                                    {oneCategId.name}
                                </option>
                            ))}
                    </select>
                </>
            )}
            {!isAddCategory ? (
                <button
                    onClick={() => setIsAddCategory(!isAddCategory)}
                    className="bg-blue-600 rounded-md p-2 my-3 text-white"
                >
                    إضافة التصنيف
                </button>
            ) : (
                <span>
                    <button
                        onClick={addCategorry}
                        className="bg-green-500 rounded-md p-2 my-3 text-white"
                    >
                        تأكيد إضافة التصنيف
                    </button>
                    <button
                        onClick={() => setIsAddCategory(!isAddCategory)}
                        className="bg-red-600 mx-1 rounded-md p-2 text-white"
                    >
                        إلغاء
                    </button>
                </span>
            )}
        </div>
    );
};

export default AddCategories;
