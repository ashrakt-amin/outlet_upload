import React, { useEffect, useState } from "react";

import { useNavigate } from "react-router-dom";
import axios from "axios";
// import TextEditorFunction from "../../TextEditorClassComponent/TextEditorFunction";
import TextEditorFunction from "../../TraderDashboard/TextEditorClassComponent/TextEditorFunction";

import "./addproducts.scss";

const AddProductsToTraders = ({ traderInfo, getInfoAgainFunc }) => {
    const label = { inputProps: { "aria-label": "Checkbox demo" } };

    const [allSubCategories, setallSubCategories] = useState([]);

    const [isAddProduct, setIsAddProduct] = useState(false);

    const navig = useNavigate();

    const [apiMessage, setApiMessage] = useState("");

    const [salePrice, setSalePrice] = useState("");

    const [buyPrice, setBuyPrice] = useState("");

    const [validationMsg, setValidationMsg] = useState("");

    // const [traderInfo, setTraderInfo] = useState({});

    // (----------------------------- (Product Info) -----------------------------)
    const [productName, setProdcutName] = useState("");

    const [itemCode, setItemCode] = useState("");

    const [imgVal, setImgVal] = useState(null);

    // (----------------------------- (types التصنيفات select) -----------------------------)
    const [categoriesArray, setCategoriesArray] = useState([]);
    const [categoryId, setcategoryId] = useState("");
    const [seletedSubName, setSeletedSubName] = useState("");
    // (----------------------------- (types التصنيفات select) -----------------------------)

    // وحدة المنتج
    const [itemsUnitsArr, setItemsUnitsArr] = useState([]);
    const [itemUnitId, setItemUnitId] = useState("1");
    // item unit id وحدة المنتج

    // العدد داخل الوحدة
    const [unitPartsCount, setUnitPartsCount] = useState("1");
    // العدد داخل الوحدة

    const [productDescription, setProductDescription] = useState("");

    // الشركة المصنعة او المنتجة
    const [manufactoryArray, setManufactoryArray] = useState([]);
    const [manufactoryID, setManufactoryID] = useState("");

    // الشركة المصنعة او المنتجة

    // الوكيل
    const [agentId, setAgentId] = useState("");
    // الوكيل

    // (----------------------------- (manufactory الشركة المستوردة   select) -----------------------------)
    const [importedCompArray, setImportedCompArray] = useState([]);
    const [importedCompId, setImportedCompId] = useState("");
    // الشركة المستوردةselect

    // الشركة الموزعة
    const [distributeCompaniesArray, setDistributeCompaniesArray] = useState(
        []
    );
    const [distributeCompanyId, setDistributeCompanyId] = useState("");
    // الشركة الموزعة select

    //  (---------------------- discount ------------------- )
    const [discountValue, setDiscountValue] = useState("");
    const [precentDiscount, setPrecentDiscount] = useState("");
    const [discountByPercentage, setDiscountByPercentage] = useState("");
    useEffect(() => {
        let discountAmount = (discountByPercentage * salePrice) / 100;
        setPrecentDiscount(discountAmount);
        setDiscountValue(discountAmount);
    }, [discountByPercentage, salePrice]);

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
                            `${process.env.MIX_APP_URL}/api/categories`,
                            {
                                cancelRequest: cancelRequest.token,
                            }
                        );
                        setCategoriesArray(res.data.data);
                        console.log(res);
                    } catch (error) {
                        console.warn(error.message);
                    }
                };
                getCategories();

                // وحدات المنتج
                const getItemUnits = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}/api/itemUnits`,
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

                // الشركة المستوردة
                // const getImportedCompany = async () => {
                //     try {
                //         const res = await axios.get(
                //             `${process.env.MIX_APP_URL}/api/importers`,
                //             {
                //                 cancelRequest: cancelRequest.token,
                //             }
                //         );
                //         setImportedCompArray(res.data.data);
                //     } catch (error) {
                //         console.warn(error.message);
                //     }
                // };
                // getImportedCompany();
                // الشركة المنتجة او المصنعة
                // const getManufactorCompanies = async () => {
                //     try {
                //         const res = await axios.get(
                //             `${process.env.MIX_APP_URL}/api/manufactories`,
                //             {
                //                 cancelRequest: cancelRequest.token,
                //             }
                //         );
                //         setManufactoryArray(res.data.data);
                //         console.log(res.data.data);
                //     } catch (error) {
                //         console.warn(error.message);
                //     }
                // };
                // getManufactorCompanies();
                // الشركة   الموزعة
                // const getDistributeCompanies = async () => {
                //     try {
                //         const res = await axios.get(
                //             `${process.env.MIX_APP_URL}/api/companies`,
                //             {
                //                 cancelRequest: cancelRequest.token,
                //             }
                //         );
                //         setDistributeCompaniesArray(res.data.data);
                //     } catch (error) {
                //         console.warn(error.message);
                //     }
                // };
                // getDistributeCompanies();

                const getVolumes = async () => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}/api/volumes`,
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
            setValidationMsg("اختر صور المنتج");
            setTimeout(() => {
                setValidationMsg("");
            }, 2000);
            return;
        }
        addProductFunc();
    };

    const emptyInputs = () => {
        setProdcutName("");
        setImgVal(null);
        setSalePrice("");
        setItemCode("");
        setcategoryId("");
        setSeletedSubName("");
        setItemUnitId("1");
        setUnitPartsCount("1");
        setProductDescription("");
        setDiscountValue("");
        setPrecentDiscount("");
        setDiscountByPercentage("");
    };

    const addProductFunc = async () => {
        let traderTk = JSON.parse(localStorage.getItem("uTk"));

        const fData = new FormData();

        fData.append("name", productName);

        imgVal.map((el) => {
            fData.append("img[]", el);
        });

        fData.append("category_id", categoryId); // اسم التصنيف

        fData.append("item_code", itemCode); // كود المنتج

        fData.append("item_unit_id", itemUnitId); // وحدة المنتج _ قطعة+-وحدة-علبة

        fData.append("unit_parts_count", unitPartsCount); // العدد داخل الوحدة  او العلبة

        fData.append("discount", discountByPercentage); // الخصم

        fData.append("sale_price", salePrice); //

        fData.append("buy_price", buyPrice); //

        fData.append("buy_discount", discountValue); // خصم الشراء

        fData.append("trader_id", traderInfo.id);

        // fData.append("available", isAvalable);

        fData.append("description", productDescription);

        fData.append("manufactory_id", manufactoryID); //  الشركة المنتجة او المصنعة

        fData.append("agent_id", agentId); //  الوكيل

        fData.append("company_id", distributeCompanyId); // الشركة الموزعة

        fData.append("importer_id", importedCompId); // الشركة المستوردة

        try {
            const res = await axios.post(
                `${process.env.MIX_APP_URL}/api/items`,
                fData,
                {
                    headers: {
                        Authorization: `Bearer ${traderTk}`,
                    },
                }
            );
            emptyInputs();
            setApiMessage(res.data.message);
            getInfoAgainFunc();
            setTimeout(() => {
                setApiMessage("");
            }, 2000);
            console.log(res.data.message);
        } catch (er) {
            console.log(er.response);
            console.log(er);
        }
    };
    // (------------------------ (End adding product Function) -----------------------------)
    const whatCategory = (e) => {
        setcategoryId(e.target.value);
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

    const whatSub = (sub) => {
        console.log(sub);
        setcategoryId(sub.id);
        setSeletedSubName(sub.name);
    };

    return (
        <div>
            {/* <h1 className="p-1 bg-green-500 rounded-sm text-center text-white my-4">
                اضافة منتجات
            </h1> */}
            {apiMessage.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 p-1 bg-green-500">
                    {apiMessage}
                </div>
            )}
            {validationMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 p-1 bg-red-500">
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

                    <div className="sale-price-div">
                        <div className="mt-3 mb-2">سعر البيع</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="number"
                            value={salePrice}
                            min={1}
                            placeholder="سعر الشراء"
                            onChange={(e) => setSalePrice(e.target.value)}
                        />
                    </div>

                    <div className="discount-div">
                        <div className="">إختر الخصم (ضع النسبة)</div>
                        <div>
                            <div>{precentDiscount} جنية</div>
                            <input
                                className="border-none shadow-md rounded-md"
                                type="text"
                                min={0}
                                value={discountByPercentage}
                                placeholder="10%"
                                onChange={(e) =>
                                    setDiscountByPercentage(e.target.value)
                                }
                            />
                        </div>
                    </div>

                    <div className="product-name-div">
                        <div className="mt-3 mb-2">كودالمنتج</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="text"
                            value={itemCode}
                            placeholder="كودالمنتج"
                            onChange={(e) => setItemCode(e.target.value)}
                        />
                    </div>

                    {/*------------------------ Photo Table --------------------------------*/}
                    <div className="img-div bg-slate-300 p-2 rounded-md">
                        <h1 className="my-2">اختر صور المنتج</h1>
                        <input multiple type="file" onChange={handleImg} />
                    </div>
                    {/*------------------------ Photo Table --------------------------------*/}

                    {/*-------------------- sub Category (Select list) ---------------------*/}
                    {/*-------------------------- (اسم الصنيف) ----------------------------*/}
                    <div className="types-div mt-3">
                        <h1 className="font-bold"> إختر التصنيف </h1>
                        <h1>
                            التصنيف الذى تم اختيارة :{" "}
                            {seletedSubName.length > 0 ? (
                                <span className="border-b-2 font-bold shadow-md">
                                    {seletedSubName}
                                </span>
                            ) : (
                                "لم يتم الاختيار بعد"
                            )}
                        </h1>
                        <div className="category-container-add-prodcut">
                            <div className="main-category-btn relative rounded-md p-1">
                                <span className="cursor-pointer">
                                    التصنيفات
                                </span>
                                <div className="category-div-toggle hidden bg-blue-600 rounded-md">
                                    {categoriesArray &&
                                        categoriesArray.map((categ) => (
                                            <div
                                                key={categ.id}
                                                className="categoy-name cursor-pointer relative"
                                            >
                                                <h1 className="p-1 m-3">
                                                    {categ.name}
                                                </h1>
                                                <div className="subCateg bg-white shadow-lg rounded-md absolute hidden">
                                                    <div className="subCategory-div">
                                                        {categ.subCategories &&
                                                            categ.subCategories.map(
                                                                (sub) => (
                                                                    <button
                                                                        onClick={() =>
                                                                            whatSub(
                                                                                sub
                                                                            )
                                                                        }
                                                                        className="rounded-md shadow-md text-black"
                                                                        key={
                                                                            sub.id
                                                                        }
                                                                    >
                                                                        {
                                                                            sub.name
                                                                        }
                                                                    </button>
                                                                )
                                                            )}
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                </div>
                            </div>
                        </div>
                        {/* <select
                            className="rounded-md cursor-pointer"
                            onChange={whatCategory}
                            name="type"
                            id="type"
                            value={categoryId}
                        >
                            <option value={"0"}>لم تختر بعد</option>
                            {categoriesArray &&
                                categoriesArray.map((oneType) => (
                                    <option value={oneType.id} key={oneType.id}>
                                        {oneType.name}
                                    </option>
                                ))}
                        </select> */}
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

                    {/* <div className="discount-div">
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
                    </div> */}

                    {/* <div className="import-checkbox-div mt-4 p-1 rounded-md shadow-md w-fit h-fit">
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
                    </div> */}

                    {/*---------------------- الشركة المصنعة او المنتجة  ---------------------*/}
                    {/* <div className="manufactories-companies mt-3">
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
                    </div> */}
                    {/*---------------------- الشركة المصنعة او المنتجة  ---------------------*/}

                    {/* ----------------------- Distribute Company Select ------------------- */}
                    {/* <div className="distribute-companies mt-3">
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
                    </div> */}
                    {/* ----------------------- Distribute Company Select ------------------- */}

                    {/* ----------------------- Distribute Company Select ------------------- */}
                    {/* <div className="distribute-companies mt-3">
                        <h1> إختر الوكيل</h1>
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
                    </div> */}
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
