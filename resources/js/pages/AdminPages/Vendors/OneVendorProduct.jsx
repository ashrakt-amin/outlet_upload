import axios from "axios";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import UpdateTraderProductModal from "../../TraderDashboard/TraderModals/UpdateTraderProductInputs";
import UpdateImageTrader from "../../TraderDashboard/TraderProductDetails/UpdateImageTrader";
import UpdateProductsImgsInDash from "./UpdateProductsImgsInDash";

const OneVendorProduct = () => {
    const [itemInfo, setItemInfo] = useState({});
    const { id } = useParams();

    const [isUpdateProduct, setisUpdateProduct] = useState(false);

    const [isAddImages, setIsAddImages] = useState(false);

    const [images, setImages] = useState([]);

    const [addToProductMsg, setAddToProductMsg] = useState("");
    const [successMsg, setSuccessMsg] = useState("");

    const [colors, setColors] = useState([]);

    const [sizes, setSizes] = useState([]);

    const [stockCount, setStockCount] = useState("1");

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
                    `http://127.0.0.1:8000/api/items/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${getToken}`,
                        },
                    }
                );
                console.log(res.data.data);
                setItemInfo(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getLevels();
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
                `http://127.0.0.1:8000/api/itemImages`,
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
        if (e.target.value != "0") {
            setColorIdSelect(e.target.value);
        } else {
            console.log("zero not valid");
        }
        console.log(e.target.value);
    };
    // (------------------------ handle Size select ----------------------)

    // (------------------------ handle Color select ----------------------)
    const whatColor = (e) => {
        if (e.target.value != "0") {
            setSizeIdSelect(e.target.value);
        } else {
            console.log("zero not valid");
        }
        console.log(e.target.value);
    };
    // (------------------------ handle Color select ----------------------)

    // (------------------------ handle Color select ----------------------)
    const whatVolume = (e) => {
        // if (e.target.value != "0") {
        //     setVolumeIdSelect(e.target.value);
        // } else {
        //     console.log("zero not valid");
        // }
        console.log(e.target.value);
    };
    // (------------------------ handle Color select ----------------------)

    const addStkSizClr = async (prodcutInf) => {
        const { id } = prodcutInf;
        if (stockCount < "1") {
            setAddToProductMsg("يجب ملئ البيانات بطريقة صحيحة");
            setTimeout(() => {
                setAddToProductMsg("");
            }, 3000);
        } else {
            try {
                const res = await axios.post(
                    `http://127.0.0.1:8000/api/colorSizeStocks`,
                    {
                        item_id: id,
                        color_id: colorIdSelect,
                        size_id: sizeIdSelect,
                        stock: stockCount,
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

    return (
        <div dir="rtl" className="p-3">
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
                            اسم المنتج {itemInfo.name}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            سعر المنتج {itemInfo.sale_price}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            كود المنتج{itemInfo.code}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            عدد المنتج{itemInfo.stock}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            عدد المشاهدات{itemInfo.views}
                        </h1>

                        <div className="product-colors mt-4 my-2 shadow-md rounded-md p-2">
                            <h1>الوان المنتج</h1>
                            <div className="flex gap-2">
                                {itemInfo.colors &&
                                    itemInfo.colors.map((color) => (
                                        <div
                                            className="shadow-md p-1 rounded-md"
                                            key={color.id}
                                        >
                                            {color.name}
                                        </div>
                                    ))}
                            </div>
                        </div>

                        <div className="product-colors mt-4 my-2 shadow-md rounded-md p-2">
                            <h1>مقاسات المنتج</h1>
                            <div className="flex gap-2">
                                {itemInfo.sizes &&
                                    itemInfo.sizes.map((size) => (
                                        <div
                                            className="shadow-md p-1 rounded-md"
                                            key={size.id}
                                        >
                                            {size.name}
                                        </div>
                                    ))}
                            </div>
                        </div>

                        <h1 className="my-2 shadow-md rounded-md p-2">
                            الشركة المصنعة: {itemInfo.manufactory?.name}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            الشركة الموزعة: {itemInfo?.company?.name}
                        </h1>
                        <h1 className="my-2 shadow-md rounded-md p-2">
                            الشركة المستوردة: {itemInfo?.importer?.name}
                        </h1>

                        <h1 className="my-2 shadow-md rounded-md p-2">
                            عدد زيارة المنتج:{" "}
                            {itemInfo.views == false
                                ? "لا يوجد بعد"
                                : itemInfo.views}
                        </h1>

                        <div className="descripion-container">
                            <div
                                className="description-div"
                                dangerouslySetInnerHTML={{
                                    __html: itemInfo.description,
                                }}
                            />
                        </div>
                    </div>
                </details>

                <div className="add-imgs my-5 bg-slate-300 p-3 border-2 rounded-md border-indigo-600">
                    <h1>اضافة صور للمنتج</h1>
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
                            {colors &&
                                colors.map((onecolor) => (
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
                            {sizes &&
                                sizes.map((onesize) => (
                                    <option value={onesize.id} key={onesize.id}>
                                        {onesize.name}
                                    </option>
                                ))}
                        </select>
                    </div>
                    {/* ------------------------------- Size Select---------------------------------- */}

                    {/* ------------------------------- Size Select---------------------------------- */}
                    <div dir="rtl" className="size-select-div">
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
                    </div>
                    {/* ------------------------------- Size Select---------------------------------- */}

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
