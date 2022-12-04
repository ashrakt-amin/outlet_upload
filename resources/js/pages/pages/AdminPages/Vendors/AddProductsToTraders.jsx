import React, { useEffect, useState } from "react";

import Checkbox from "@mui/material/Checkbox";

import { json, useNavigate } from "react-router-dom";
import axios from "axios";
// import TextEditorFunction from "../../TextEditorClassComponent/TextEditorFunction";
import TextEditorFunction from "../../TraderDashboard/TextEditorClassComponent/TextEditorFunction";

const AddProductsToTraders = ({ traderInfo }) => {
    const label = { inputProps: { "aria-label": "Checkbox demo" } };

    const navig = useNavigate();

    const [apiMessage, setApiMessage] = useState("");

    console.log(traderInfo);

    const [validationMsg, setValidationMsg] = useState("");

    // const [traderInfo, setTraderInfo] = useState({});

    // (----------------------------- (Product Info) -----------------------------)
    const [productName, setProdcutName] = useState("");

    const [imgVal, setImgVal] = useState(null);

    // (----------------------------- (types التصنيفات select) -----------------------------)
    const [typesArray, setTypesArray] = useState([]);
    const [typeId, setTypeId] = useState("1");
    // (----------------------------- (types التصنيفات select) -----------------------------)

    // (----------------------------- (item unit id وحدة المنتج-- select) -----------------------------)
    const [itemsUnitsArr, setItemsUnitsArr] = useState([]);
    const [itemUnitId, setItemUnitId] = useState("1"); // send as (id):Number from select
    // (----------------------------- (item unit id وحدة المنتج-- select) -----------------------------)

    // (----------------------------- (العدد داخل الوحدة) -----------------------------)
    const [unitPartsCount, setUnitPartsCount] = useState("1");
    // (----------------------------- (العدد داخل الوحدة) -----------------------------)

    const [productDescription, setProductDescription] = useState("");

    // (----------------------------- (manufactory الشركة المصنعة او المنتجة select) -----------------------------)
    const [manufactoryArray, setManufactoryArray] = useState([]);
    const [manufactoryID, setManufactoryID] = useState("");

    // (----------------------------- (manufactory الشركة المصنعة او المنتجة select) -----------------------------)

    // (----------------------------- ( Agent Id) -----------------------------)
    const [agentId, setAgentId] = useState("");
    // (----------------------------- ( Agent Id) -----------------------------)

    // (----------------------------- (manufactory الشركة المستوردة   select) -----------------------------)
    const [importedCompArray, setImportedCompArray] = useState([]);
    const [importedCompId, setImportedCompId] = useState("");
    // (----------------------------- (manufactory الشركة المستوردة  select) -----------------------------)

    // (----------------------------- (Distribute companies الشركة الموزعة select) ------------------------)
    const [distributeCompaniesArray, setDistributeCompaniesArray] = useState(
        []
    );
    const [distributeCompanyId, setDistributeCompanyId] = useState("");
    // (----------------------------- (Distribute companies الشركة الموزعة select) ------------------------)

    //  (---------------------- discount ------------------- )
    const [discountValue, setDiscountValue] = useState("");
    const [precentDiscount, setPrecentDiscount] = useState("");
    const [discountByPercentage, setDiscountByPercentage] = useState("");
    // useEffect(() => {
    //     let discountAmount = (discountByPercentage * salePrice) / 100;
    //     setPrecentDiscount(discountAmount);
    //     setDiscountValue(discountAmount);
    // }, [discountByPercentage]);

    //  (---------------------- discount ------------------- )

    const [successMsg, setSuccessMsg] = useState("");

    //1- if token in local storage then make request
    // if response true get all data regarde to this trader
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let userToken = JSON.parse(localStorage.getItem("uTk"));
        const getTraders = async () => {
            try {
                const getCategories = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}api/types`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        setTypesArray(res.data.data);
                        console.log(res);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getCategories();

                // الشركة المستوردة
                const getImportedCompany = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}api/importers`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        setImportedCompArray(res.data.data);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getImportedCompany();
                // وحدات المنتج
                const getItemUnits = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}api/itemUnits`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        console.log(res.data.data);
                        setItemsUnitsArr(res.data.data);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getItemUnits();

                // الشركة المنتجة او المصنعة
                const getManufactorCompanies = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}api/manufactories`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        setManufactoryArray(res.data.data);
                        console.log(res.data.data);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getManufactorCompanies();

                // الشركة   الموزعة
                const getDistributeCompanies = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}api/companies`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        setDistributeCompaniesArray(res.data.data);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getDistributeCompanies();

                const getVolumes = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}api/volumes`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        // setDistributeCompaniesArray(res.data.data);
                        console.log(res.data.data);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getVolumes();
                // axios.defaults.withCredentials = true;
                // axios
                //     .get(`${process.env.MIX_APP_URL}` + "sanctum/csrf-cookie")
                //     .then(async (res1) => {
                //     });
            } catch (er) {
                console.log(er);
            }
        };
        getTraders();

        return () => {
            cancelRequest.cancel();
        };
    }, []);

    // (------------------------ (Start adding product Function) -----------------------------)
    const validFirst = () => {
        let regNum = /[0-9]/;

        if (imgVal == null) {
            console.log("no images");
            setValidationMsg("اختر صور المنتج");
            setTimeout(() => {
                setValidationMsg("");
            }, 3000);
            return;
        }
        // if (
        //     salePrice.match(regNum) &&
        //     buyPrice.match(regNum) &&
        //     productName != "" &&
        //     unitPartsCount.match(regNum)
        // ) {
        //     console.log("valid");
        // } else {
        //     console.log("not valid");
        // }
        addProductFunc();
    };

    const addProductFunc = async () => {
        let traderTk = JSON.parse(localStorage.getItem("uTk"));

        const fData = new FormData();

        fData.append("name", productName);

        imgVal.map((el) => {
            fData.append("img[]", el);
        });
        fData.append("type_id", typeId); // اسم التصنيف

        fData.append("item_unit_id", itemUnitId); // وحدة المنتج _ قطعة+-وحدة-علبة

        fData.append("unit_parts_count", unitPartsCount); // العدد داخل الوحدة او القطعة او العلبة

        fData.append("discount", discountValue); // الخصم

        fData.append("trader_id", traderInfo.id);

        fData.append("description", productDescription);

        fData.append("manufactory_id", manufactoryID); //  الشركة المنتجة او المصنعة

        fData.append("agent_id", agentId); //  الشركة المنتجة او المصنعة

        fData.append("company_id", distributeCompanyId); // الشركة الموزعة

        fData.append("importer_id", importedCompId); // الشركة المستوردة

        try {
            const res = await axios.post(
                `${process.env.MIX_APP_URL}api/items`,
                fData,
                {
                    headers: {
                        Authorization: `Bearer ${traderTk}`,
                    },
                }
            );
            setApiMessage(res.data.message);
            setTimeout(() => {
                setApiMessage("");
            }, 4000);
            console.log(res.data.message);
        } catch (er) {
            console.log(er.response);
        }
    };
    // (------------------------ (End adding product Function) -----------------------------)
    const whatType = (e) => {
        setTypeId(e.target.value);
    };

    const logOutTrader = () => {
        localStorage.removeItem("trTk");
        navig("/");
    };

    const handleImg = (e) => {
        setImgVal([...e.target.files]);
    };

    // (------------------------ handle item unints select ----------------------)
    const whatItem = (e) => {
        if (e.target.value != "0") {
            setItemUnitId(e.target.value);
        } else {
            console.log("zero not valid");
        }
        console.log(e.target.value);
    };
    // (------------------------ handle item unints select ----------------------)

    // (------------------------ handle Text Editor Value ----------------------)
    const textEditorValue = (text) => {
        setProductDescription(text);
    };
    // (------------------------ handle Text Editor Value ----------------------)

    const whatImportedComp = (impCompany) => {
        setImportedCompId(impCompany.target.value);
        // if (impCompany.target.value != "0") {
        // } else {
        //     console.log("zero not valid");
        // }
    };

    const whatManufactor = (manufactor) => {
        setManufactoryID(manufactor.target.value);
        // if (manufactor.target.value != "0") {
        // } else {
        //     console.log("zero not valid");
        // }
    };

    const whatDistribute = (distribure) => {
        setDistributeCompanyId(distribure.target.value);
        // if (distribure.target.value != "0") {
        // } else {
        //     console.log("zero not valid");
        // }
    };

    return (
        <div>
            <h1 className="p-1 bg-green-500 rounded-sm text-center text-white my-4">
                اضافة منتجات
            </h1>
            {apiMessage.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-green-500">
                    {apiMessage}
                </div>
            )}
            {validationMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-red-500">
                    {validationMsg}
                </div>
            )}

            <div className="add-product-inputs ">
                <div className="grid lg:grid-cols-2 gap-x-12 gap-y-3 p-3">
                    <div className="product-name-div">
                        <div className="mt-3 mb-2">إسم المنتج</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="text"
                            value={productName}
                            placeholder="اسم المنتج"
                            onChange={(e) => setProdcutName(e.target.value)}
                        />
                    </div>
                    {/*------------------------ Photo Table --------------------------------*/}
                    <div className="img-div bg-slate-300 p-2 rounded-md">
                        <h1 className="my-2">اختر صور المنتج</h1>
                        <input multiple type="file" onChange={handleImg} />
                    </div>
                    {/*------------------------ Photo Table --------------------------------*/}

                    {/*-------------------- sub Category (Select list) ---------------------*/}
                    {/*-------------------------- (اسم الصنيف) -------------------------------*/}
                    <div className="types-div mt-3">
                        <h1> إختر التصنيف </h1>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatType}
                            name="type"
                            id="type"
                            value={typeId}
                        >
                            {/* <option value={"0"}>لم تختر بعد</option> */}
                            {typesArray &&
                                typesArray.map((oneType) => (
                                    <option value={oneType.id} key={oneType.id}>
                                        {oneType.name}
                                    </option>
                                ))}
                        </select>
                    </div>

                    {/*-------------------- sub Category (Select list) ---------------------*/}

                    {/* ------------------------------- قطعة أو علبة ---------------------------------- */}
                    <div className="item-units-select-div p-2 rounded-md flex items-start gap-1 flex-col">
                        <span>اختر وحدة المنتج</span>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatItem}
                            name="itemunit"
                            id="itemunit"
                            value={itemUnitId}
                        >
                            {/* <option value={"0"}>لم تختر بعد</option> */}
                            {itemsUnitsArr &&
                                itemsUnitsArr.map((oneItemUnit) => (
                                    <option
                                        value={oneItemUnit.id}
                                        key={oneItemUnit.id}
                                    >
                                        {oneItemUnit.name}
                                    </option>
                                ))}
                        </select>
                    </div>
                    {/* ------------------------------- قطعة أو علبة ---------------------------------- */}

                    <div className="unit-parts-count-div">
                        <div className="mt-3 mb-2"> إختر العدد داخل الوحدة</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="number"
                            min={1}
                            value={unitPartsCount}
                            placeholder="العدد داخل القطعة او الوحدة او العلبة"
                            onChange={(e) => setUnitPartsCount(e.target.value)}
                            title="Title"
                        />
                    </div>

                    <div className="discount-div">
                        <div className="">إختر طريقة الخصم</div>
                        <button
                            onClick={opnDiscByPound}
                            className={`${
                                isDiscountPrice ? "bg-green-400" : ""
                            } mx-2 p-1 mb-2 shadow-md rounded-md`}
                        >
                            الخصم بالجنية
                        </button>
                        <button
                            onClick={opnDiscByPercentage}
                            className={`${
                                isDiscountPerc ? "bg-green-400" : ""
                            } mx-2 p-1 mb-2 shadow-md rounded-md`}
                        >
                            الخصم بالنسبة
                        </button>
                        {isDiscountPerc && (
                            <button
                                onClick={cancelDiscount}
                                className={`mt-3 mx-2 p-1 bg-red-400 mb-2 shadow-md rounded-md`}
                            >
                                إلغاء الخصم
                            </button>
                        )}
                        {isDiscountPrice && (
                            <button
                                onClick={cancelDiscount}
                                className="mt-3 mx-2 p-1 bg-red-400 mb-2 shadow-md rounded-md"
                            >
                                إلغاء الخصم
                            </button>
                        )}
                        {isDiscountPrice && (
                            <input
                                className="border-none shadow-md rounded-md"
                                type="number"
                                min={0}
                                value={discountValue}
                                placeholder="10"
                                onChange={(e) =>
                                    setDiscountValue(e.target.value)
                                }
                            />
                        )}
                        {isDiscountPerc && (
                            <div>
                                <div>{precentDiscount} جنية</div>
                                <input
                                    className="border-none shadow-md rounded-md"
                                    type="text"
                                    min={0}
                                    value={discountByPercentage}
                                    placeholder=" اكتب القيمة فقط مثال: 10"
                                    onChange={(e) =>
                                        setDiscountByPercentage(e.target.value)
                                    }
                                />
                            </div>
                        )}
                    </div>

                    <div className="import-checkbox-div mt-4 p-1 rounded-md shadow-md w-fit h-fit">
                        <div>هل هذا المنتج مستورد ؟</div>
                        <div className="types-div">
                            <select onChange={whatImportedComp} name="" id="">
                                <option value="0">اخترالشركة المستوردة</option>
                                {importedCompArray &&
                                    importedCompArray.map((oneImporedComp) => (
                                        <option
                                            value={oneImporedComp.id}
                                            key={oneImporedComp.id}
                                        >
                                            {oneImporedComp.name}
                                        </option>
                                    ))}
                            </select>
                        </div>
                    </div>

                    {/*---------------------- الشركة المصنعة او المنتجة  ---------------------*/}
                    <div className="manufactories-companies mt-3">
                        <h1>إختر الشركات المصنعة</h1>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatManufactor}
                            name=""
                            id=""
                        >
                            <option value={"0"}>لم تختر بعد</option>
                            {manufactoryArray &&
                                manufactoryArray.map((oneManufactor) => (
                                    <option
                                        value={oneManufactor.id}
                                        key={oneManufactor.id}
                                    >
                                        {oneManufactor.name}
                                    </option>
                                ))}
                        </select>
                    </div>
                    {/*---------------------- الشركة المصنعة او المنتجة  ---------------------*/}

                    {/* ----------------------- Distribute Company Select ------------------- */}
                    <div className="distribute-companies mt-3">
                        <h1> إختر الشركة الموزعة</h1>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatDistribute}
                            name=""
                            id=""
                        >
                            <option value={"0"}>لم تختر بعد</option>

                            {distributeCompaniesArray &&
                                distributeCompaniesArray.map(
                                    (oneDistributeComp) => (
                                        <option
                                            value={oneDistributeComp.id}
                                            key={oneDistributeComp.id}
                                        >
                                            {oneDistributeComp.name}
                                        </option>
                                    )
                                )}
                        </select>
                    </div>
                    {/* ----------------------- Distribute Company Select ------------------- */}

                    <TextEditorFunction textEditorValue={textEditorValue} />
                </div>
                <button
                    onClick={validFirst}
                    className="bg-blue-600 mx-3 rounded-md p-2 my-3 text-white"
                >
                    إضافة هذا المنتج
                </button>
            </div>
        </div>
    );
};

export default AddProductsToTraders;
