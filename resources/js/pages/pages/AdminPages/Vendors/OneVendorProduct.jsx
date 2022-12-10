import axios from "axios";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import UpdateTraderProductModal from "../../TraderDashboard/TraderModals/UpdateTraderProductInputs";
import UpdateProductsImgsInDash from "./UpdateProductsImgsInDash";

const OneVendorProduct = () => {
    const [itemInfo, setItemInfo] = useState({});
    const { id } = useParams();

    const [categoreisArray, setCategoriesArray] = useState([]);

    // item_id: id,
    // stock: stockCount,
    const [stockCount, setStockCount] = useState("1");

    const [stockCode, setStockCode] = useState("1");

    // sale_price: sale_price,
    const [salePrice, setSalePrice] = useState("1");

    // buy_price: buy_price,
    const [buyPrice, setBuyPrice] = useState("1");

    // trader_id: trader.id,
    const [traderId, setTraderId] = useState("1");

    // buy_discount: discount,
    const [buyDiscount, setBuyDiscount] = useState("1");

    // availalbe: isAvialabel,
    const [isAvialabel, setisAvialabel] = useState("");

    // approved: isApproved,
    const [isApproved, setisApproved] = useState("");

    // code: productCode,
    const [productCode, setproductCode] = useState("");

    // min_quantity: minQuantity,
    const [minQuantity, setminQuantity] = useState("");

    // barCode: productBarCode,
    const [productBarCode, setproductBarCode] = useState("");

    // spare_barcode:spareBarCode,
    const [spareBarCode, setSpareBarCode] = useState("");

    // (----------------------------- ( Select States ) -----------------------------)
    // color_id: colorIdSelect,
    const [colorIdSelect, setColorIdSelect] = useState("");

    // size_id: sizeIdSelect,
    const [sizeIdSelect, setSizeIdSelect] = useState("");

    // volume_id: volumeIdSelect,
    const [volumeIdSelect, setVolumeIdSelect] = useState("");
    // (----------------------------- ( Select States ) -----------------------------)
    // weight_id: "",
    const [weightId, setweightId] = useState("");

    // season_id: "",
    const [seasonId, setseasonId] = useState("");

    const [manufactureDate, setmanufactureDate] = useState("");
    // manufacture_data: manufactureData,

    // expire_date:expireDate,
    const [expireDate, setexpireDate] = useState("");

    // stock_discount: stockDiscount,
    const [stockDiscount, setstockDiscount] = useState("");

    // discount_start_date: discountStartDate,
    const [discountStartDate, setdiscountStartDate] = useState("");

    // discount_end_date: discountEndDate,
    const [discountEndDate, setdiscountEndDate] = useState("");

    const [barCode, setBareCode] = useState("");

    // const [barCode, setBarCode] = useState("");

    const [isUpdateProduct, setisUpdateProduct] = useState(false);

    const [isAddImages, setIsAddImages] = useState(false);

    const [images, setImages] = useState([]);

    const [addToProductMsg, setAddToProductMsg] = useState("");

    const [successMsg, setSuccessMsg] = useState("");

    const [fetchAgain, setFetchAgain] = useState(false);

    const [colorsArray, setColorsArray] = useState([]);
    const [sizesArray, setSizesArray] = useState([]);
    const [VolumesArray, setVolumesArray] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/items/${id}`
                );
                console.log(res.data.data);
                setItemInfo(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getLevels();

        const getColors = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/colors`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setColorsArray(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getColors();
        const getSizes = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/sizes`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setSizesArray(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getSizes();

        const getVolumes = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/volumes`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setVolumesArray(res.data.data);
                console.log(res);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getVolumes();

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

        return () => {
            cancelRequest.cancel();
        };
    }, [fetchAgain]);

    const refetchFunc = () => {
        setFetchAgain(!fetchAgain);
    };

    const addImages = (e) => {
        setImages([...e.target.files]);
        setIsAddImages(!isAddImages);
    };

    const addImagesNow = async (prodcutInf) => {
        const { id } = prodcutInf;

        console.log(prodcutInf);

        const fData = new FormData();
        fData.append("item_id", id);
        images.map((img) => {
            fData.append("img[]", img);
        });

        try {
            const res = await axios.post(
                `${process.env.MIX_APP_URL}/api/itemImages`,
                fData
            );
            setFetchAgain(!fetchAgain);
            setIsAddImages(false);
            setImages([]);
        } catch (error) {
            console.log(error.message);
        }
    };

    const cancelUpdateImgs = () => {
        setImages([]);
        setIsAddImages(false);
    };

    // (------------------------ handle Size select ----------------------)
    const whatSize = (e) => {
        setColorIdSelect(e.target.value);
        console.log(e.target.value);
    };
    // (------------------------ handle Size select ----------------------)

    // (------------------------ handle Color select ----------------------)
    const whatColor = (e) => {
        setSizeIdSelect(e.target.value);
        console.log(e.target.value);
    };
    // (------------------------ handle Color select ----------------------)

    // (------------------------ handle Color select ----------------------)
    const whatVolume = (e) => {
        setVolumeIdSelect(e.target.value);
        console.log(e.target.value);
    };
    // (------------------------ handle Color select ----------------------)

    console.log(stockCode);

    const addStkSizClr = async (prodcutInf) => {
        const { id, trader } = prodcutInf;
        if (stockCount < "1") {
            setAddToProductMsg("يجب ملئ البيانات بطريقة صحيحة");
            setTimeout(() => {
                setAddToProductMsg("");
            }, 3000);
        } else {
            try {
                const res = await axios.post(
                    `${process.env.MIX_APP_URL}/api/stocks`,
                    {
                        item_id: id,
                        stock: stockCount,
                        stock_code: stockCode,
                        trader_id: trader.id,
                        season_id: "",
                        color_id: colorIdSelect,
                        size_id: sizeIdSelect,
                        weight_id: weightId,
                        season_id: seasonId,
                        sale_price: salePrice,
                        // buy_price: buyPrice,
                        // buy_discount: discount,
                        // weight_id: "",
                        // volume_id: volumeIdSelect,
                        // manufacture_data: manufactureDate,
                        // expire_date: expireDate,
                        // stock_discount: stockDiscount,
                        // discount_start_date: discountStartDate,
                        // discount_end_date: discountEndDate,
                    }
                );
                console.log(res);
                setFetchAgain(!fetchAgain);
                setSuccessMsg(res.data.message);
                setTimeout(() => {
                    setSuccessMsg("");
                }, 3000);
                // setColors(res.data.data);
            } catch (er) {
                console.log(er);
            }
        }
    };

    //  (---------------------- discount ------------------- )
    // const [discountValue, setDiscountValue] = useState("");
    // const [precentDiscount, setPrecentDiscount] = useState("");
    // const [discountByPercentage, setDiscountByPercentage] = useState("");
    // useEffect(() => {
    //     let discountAmount = (discountByPercentage * salePrice) / 100;
    //     setPrecentDiscount(discountAmount);
    //     setDiscountValue(discountAmount);
    // }, [discountByPercentage]);

    //  (---------------------- discount ------------------- )

    const [isDiscountPerc, setIsDicountPerc] = useState(false);
    const [isDiscountPrice, setIsDicountPrice] = useState(false);
    // (------------------------ open discount handle ----------------------)
    const opnDiscByPound = () => {
        setIsDicountPrice(true);
        setIsDicountPerc(false);
    };

    const opnDiscByPercentage = () => {
        setIsDicountPrice(false);
        setIsDicountPerc(true);
    };

    const cancelDiscount = () => {
        setIsDicountPrice(false);
        setIsDicountPerc(false);
        setDiscountValue("0");
        setPrecentDiscount("");
        setDiscountByPercentage("");
    };
    // (------------------------ open discount handle ----------------------)

    console.log(itemInfo.description);

    return (
        <div dir="rtl" className="p-3">
            {isUpdateProduct ? (
                <button
                    onClick={() => setisUpdateProduct(!isUpdateProduct)}
                    className="bg-red-600 rounded-md p-2 my-3 text-white"
                >
                    الغاء تعديل المنتج
                </button>
            ) : (
                <button
                    onClick={() => setisUpdateProduct(!isUpdateProduct)}
                    className="bg-blue-600 rounded-md p-2 my-3 text-white"
                >
                    تعديل المنتج
                </button>
            )}

            {isUpdateProduct && (
                <UpdateTraderProductModal traderProductInfo={itemInfo} />
            )}

            <div className="add-imgs my-5 bg-slate-300 p-3 border-2 rounded-md border-indigo-600">
                <h1 className="p-2 bg-white shadow-md rounded-md m-2">
                    اضافة صور للمنتج
                </h1>
                <input type="file" multiple onChange={addImages} />
                {isAddImages && (
                    <>
                        <button
                            className="bg-green-500 rounded-md p-1 text-white m-2 hover:bg-green-600 hover:px-2 transition-all"
                            onClick={() => addImagesNow(itemInfo)}
                        >
                            تأكيد اضافة الصور
                        </button>
                        <button
                            className="bg-red-500 rounded-md p-1 text-white m-2 hover:bg-green-600 hover:px-2 transition-all"
                            onClick={cancelUpdateImgs}
                        >
                            الغاء
                        </button>
                    </>
                )}
            </div>

            <h1 className="p-2 m-2 bg-green-400 rounded-md">معلومات المنتج</h1>
            <div className="info-div">
                <details className="my-3 show-product-imgs-details p-3 border-2 rounded-md border-indigo-600">
                    <summary className="cursor-pointer">
                        إظهار صور المنتج
                    </summary>

                    <div className="img-trader-array">
                        <h2 className="my-2">صور المنتج: </h2>
                        <div className="all-imgs-trader-details-product grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-2 ">
                            {itemInfo?.itemImages &&
                                itemInfo?.itemImages?.map((image) => (
                                    <UpdateProductsImgsInDash
                                        key={image.id}
                                        refetch={refetchFunc}
                                        oneImage={image}
                                    />
                                ))}
                        </div>
                    </div>
                </details>

                <details className="my-3 show-product-details p-3 border-2 rounded-md border-indigo-600">
                    <summary className="cursor-pointer">
                        اظهار معلومات المنتج
                    </summary>

                    <div className="all-product-info-container">
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            اسم المنتج : {itemInfo.name}
                        </h1>

                        <h1 className="my-2 shadow-md rounded-md p-2">
                            كود المنتج : {itemInfo.code}
                        </h1>

                        <div className="stock-item-div flex gap-4 flex-wrap">
                            {itemInfo.stocks &&
                                itemInfo.stocks.map((oneitem) => (
                                    <div
                                        className="m-1 p-1 border-2 border-blue-600 rounded-md"
                                        key={oneitem.id}
                                    >
                                        <h1> الكمية : {oneitem.stock}</h1>
                                        <h1>
                                            {" "}
                                            سعر البيع : {oneitem.sale_price}
                                        </h1>
                                        {/* <h1>
                                            {" "}
                                            سعر الشراء : {oneitem.buy_price}
                                        </h1> */}
                                        <h1> اللون : {oneitem?.color?.name}</h1>
                                        <h1> المقاس : {oneitem?.size?.name}</h1>
                                    </div>
                                ))}
                        </div>

                        {/* <h1 className="my-2 shadow-md rounded-md p-2">
                            الشركة المصنعة: {itemInfo.manufactory?.name}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            الشركة الموزعة: {itemInfo?.company?.name}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            الشركة المستوردة: {itemInfo?.importer?.name}
                        </h1> */}

                        <h1 className="my-2 shadow-md rounded-md p-2">
                            عدد زيارة المنتج:{" "}
                            {itemInfo.views == false
                                ? "لا يوجد بعد"
                                : itemInfo.views}
                        </h1>

                        <div className="descripion-container">
                            <h1>وصف المنتج</h1>
                            {itemInfo.description == false ? (
                                "لايوجد وصف"
                            ) : (
                                <div
                                    className="description-div"
                                    dangerouslySetInnerHTML={{
                                        __html: itemInfo.description,
                                    }}
                                />
                            )}
                        </div>
                    </div>
                </details>

                <div className="add-product-res-info shadow-lg border-2 rounded-md p-2">
                    {/* (------------------ Stock div ------------------) */}
                    <div className="stock-count-div position-relative">
                        {addToProductMsg.length > 0 && (
                            <h1 className="p-3 rounded-md position-absolute w-full top-1 left-0 bg-lime-500">
                                {addToProductMsg}
                            </h1>
                        )}
                        {successMsg.length > 0 && (
                            <h1 className="p-3 rounded-md position-absolute w-full top-1 left-0 bg-green-500">
                                {successMsg}
                            </h1>
                        )}

                        <h1 className="p-2 font-bold text-white m-3 bg-blue-500 text-center text-lg rounded-md">
                            إستكمال إضافة مواصفات المنتج
                        </h1>

                        <div className="mt-3 mb-2">اضافة رصيد</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="number"
                            min={1}
                            value={stockCount}
                            placeholder="رصيد القطعة"
                            onChange={(e) => setStockCount(e.target.value)}
                        />
                    </div>
                    {/* (------------------ Stock div ------------------) */}

                    {/* <div className="stock-code-div">
                        <div className="mt-3 mb-2">كود الصنف</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="text"
                            value={stockCode}
                            placeholder="رصيد القطعة"
                            onChange={(e) => set(e.target.value)}
                        />
                    </div> */}

                    {/*--------------------------------  Color Select -------------------------------- */}

                    <div dir="rtl" className="color-select-div mt-2">
                        <div>إختر اللون</div>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatColor}
                            name="itemunit"
                            id="itemunit"
                        >
                            <option value={"0"}>لم تختر بعد</option>
                            {colorsArray &&
                                colorsArray.map((onecolor) => (
                                    <option
                                        value={onecolor.id}
                                        key={onecolor.id}
                                    >
                                        {onecolor.name}
                                    </option>
                                ))}
                        </select>
                    </div>
                    {/*-------------------------------- Color Select -------------------------------- */}

                    {/* ------------------------------- Size Select---------------------------------- */}
                    <div dir="rtl" className="size-select-div">
                        <div>إختر المقاس</div>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatSize}
                            name="itemunit"
                            id="itemunit"
                        >
                            <option value={"0"}>لم تختر بعد</option>
                            {sizesArray &&
                                sizesArray.map((onesize) => (
                                    <option value={onesize.id} key={onesize.id}>
                                        {onesize.name}
                                    </option>
                                ))}
                        </select>
                    </div>
                    {/* ------------------------------- Size Select---------------------------------- */}

                    {/* ------------------------------- Size Select---------------------------------- */}
                    {/* <div dir="rtl" className="size-select-div">
                        <div>إختر الحجم</div>
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatVolume}
                            name="itemunit"
                            id="itemunit"
                        >
                            <option value={"0"}>لم تختر بعد</option>
                            {VolumesArray &&
                                VolumesArray.map((onevolume) => (
                                    <option
                                        value={onevolume.id}
                                        key={onevolume.id}
                                    >
                                        {onevolume.name}
                                    </option>
                                ))}
                        </select>
                    </div> */}
                    {/* ------------------------------- Size Select---------------------------------- */}

                    <div className="sale-price-div">
                        <div className="mt-3 mb-2">سعر البيع</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="number"
                            min={1}
                            value={salePrice}
                            placeholder="سعر البيع"
                            onChange={(e) => setSalePrice(e.target.value)}
                        />
                    </div>

                    {/* <div className="buy-price-div">
                        <div className="mt-3 mb-2">سعر الشراء</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="number"
                            value={buyPrice}
                            min={1}
                            placeholder="سعر الشراء"
                            onChange={(e) => setBuyPrice(e.target.value)}
                        />
                    </div> */}

                    <div className="product-barcode-div">
                        <div className="mt-3 mb-2">بار كود</div>
                        <input
                            className="border-none shadow-md rounded-md"
                            type="number"
                            value={stockCode}
                            placeholder="بار كود"
                            onChange={(e) => setStockCode(e.target.value)}
                        />
                    </div>

                    {/* ------------------------------- add stock,size,color button ---------------------------------- */}
                    <button
                        onClick={() => addStkSizClr(itemInfo)}
                        className="bg-blue-600 rounded-md p-2 my-3 text-white"
                    >
                        إضافة
                    </button>
                    {/* ------------------------------- add stock,size,color button ---------------------------------- */}
                </div>
            </div>
        </div>
    );
};

export default OneVendorProduct;
