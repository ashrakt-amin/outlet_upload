import axios from "axios";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import UpdateTraderProductInputs from "../TraderModals/UpdateTraderProductInputs";

import "./traderProductDetails.scss";

import ReactHtmlParser from "html-react-parser";
import Parser from "html-react-parser";
import { FormControl, InputLabel, MenuItem, Select } from "@mui/material";
import UpdateImageTrader from "./UpdateImageTrader";

const TraderProductDetails = () => {
    const { id } = useParams();

    const [addToProductMsg, setAddToProductMsg] = useState("");

    const [successMsg, setSuccessMsg] = useState("");

    const [isAddImages, setIsAddImages] = useState(false);

    const [fetchAgain, setFetchAgain] = useState(false);

    const [colors, setColors] = useState([]);

    const [sizes, setSizes] = useState([]);

    const [stockCount, setStockCount] = useState("1");

    const [isUpateProduct, setIsUpateProduct] = useState(false);

    const [procutInfo, setProductInfo] = useState({});

    const [images, setImages] = useState([]);

    // (----------------------------- ( Select States ) -----------------------------)

    const [colorIdSelect, setColorIdSelect] = useState("");

    const [sizeIdSelect, setSizeIdSelect] = useState("");
    // (----------------------------- ( Select States ) -----------------------------)

    const [colorsArray, setColorsArray] = useState([]);
    const [sizesArray, setSizesArray] = useState([]);
    const [VolumesArray, setVolumesArray] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let traderTk = JSON.parse(localStorage.getItem("trTk"));
        const getOneTraderProduct = async () => {
            let res = await axios.get(
                `${process.env.MIX_APP_URL}/api/items/${id}`,
                {
                    headers: {
                        Authorization: `Bearer ${traderTk}`,
                    },
                }
            );
            setColorsArray(res.data.data.colors);
            setSizesArray(res.data.data.sizes);
            setProductInfo(res.data.data);
        };
        getOneTraderProduct();
        const getColors = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/colors`,
                    {
                        cancelRequest: cancelRequest.token,

                        headers: {
                            Authorization: `Bearer ${traderTk}`,
                        },
                    }
                );
                setColors(res.data.data);
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

                        headers: {
                            Authorization: `Bearer ${traderTk}`,
                        },
                    }
                );
                setSizes(res.data.data);
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

                        headers: {
                            Authorization: `Bearer ${traderTk}`,
                        },
                    }
                );
                setVolumesArray(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getVolumes();
    }, [fetchAgain]);

    const updateTraderProductModal = () => {
        setIsUpateProduct(!isUpateProduct);
    };

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
                    `${process.env.MIX_APP_URL}/api/colorSizeStocks`,
                    {
                        item_id: id,
                        color_id: colorIdSelect,
                        size_id: sizeIdSelect,
                        stock: stockCount,
                    }
                    // {
                    //     headers: {
                    //         Authorization: `Bearer ${traderTk}`,
                    //     },
                    // }
                );
                console.log(res);
                setFetchAgain(!fetchAgain);
                setSuccessMsg(res.data.message);
                setTimeout(() => {
                    setSuccessMsg("");
                }, 3000);
                // setColors(res.data.data);
            } catch (error) {
                console.log(error.message);
            }
        }
    };

    const refetchFunc = () => {
        setFetchAgain(!fetchAgain);
    };

    const addImages = (e) => {
        setImages([...e.target.files]);
        setIsAddImages(!isAddImages);
    };

    const addImagesNow = async (prodcutInf) => {
        const { id } = prodcutInf;

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
        setIsAddImages(!isAddImages);
        setImages([]);
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

    return (
        <div className="trader-product-details-div">
            <h1 className="my-2">اسم المنتج : {procutInfo.name}</h1>

            <h2 className="my-2">صور المنتج: </h2>
            <div className="all-imgs-trader-details-product grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-2 ">
                {procutInfo?.itemImages &&
                    procutInfo?.itemImages?.map((image) => (
                        <UpdateImageTrader
                            key={image.id}
                            refetch={refetchFunc}
                            oneImage={image}
                        />
                    ))}
            </div>

            <h1 className="my-2 shadow-md rounded-md p-2">
                سعر المنتج: {procutInfo.sale_price}
            </h1>

            <h1 className="my-2 shadow-md rounded-md p-2">
                سعر الشراء:{" "}
                {procutInfo.buy_price != false && procutInfo.buy_price}
            </h1>
            <h1 className="my-2 shadow-md rounded-md p-2">
                كود المنتج: {procutInfo.code}
            </h1>
            <h1 className="my-2 shadow-md rounded-md p-2">
                عدد المنتج: {procutInfo.stock}
            </h1>

            <h1 className="my-2 shadow-md rounded-md p-2">
                الخصم: {procutInfo.discount} جنية
            </h1>

            <div className="product-colors mt-4">
                <h1>الوان المنتج</h1>
                <div className="flex gap-2">
                    {colorsArray &&
                        colorsArray.map((color) => (
                            <div
                                className="shadow-md p-1 rounded-md"
                                key={color.id}
                            >
                                {color.name}
                            </div>
                        ))}
                </div>
            </div>

            <div className="product-colors mt-4">
                <h1>مقاسات المنتج</h1>
                <div className="flex gap-2">
                    {sizesArray &&
                        sizesArray.map((size) => (
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
                الشركة المصنعة: {procutInfo.manufactory?.name}
            </h1>
            <h1 className="my-2 shadow-md rounded-md p-2">
                الشركة الموزعة: {procutInfo?.company?.name}
            </h1>
            <h1 className="my-2 shadow-md rounded-md p-2">
                الشركة المستوردة: {procutInfo?.importer?.name}
            </h1>

            <h1 className="my-2 shadow-md rounded-md p-2">
                عدد زيارة المنتج:{" "}
                {procutInfo.views == false ? "لا يوجد بعد" : procutInfo.views}
            </h1>
            <div className="remove-links product-description p-2 border-2 my-3">
                <h1>وصف المنتج</h1>
                {procutInfo.description ? (
                    <div
                        className="description-div px-5 my-3"
                        dangerouslySetInnerHTML={{
                            __html: procutInfo.description,
                        }}
                    />
                ) : (
                    "لا يوجد وصف للمنتج"
                )}
            </div>
            {/* <h1 className="my-2">
                معلومات اضافيه عن المنتج: {procutInfo.description}{" "}
            </h1> */}

            <div className="add-product-res-info shadow-lg border-2 rounded-md p-2">
                {/* (------------------ Stock div ------------------) */}
                {/* <div className="stock-count-div position-relative">
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
                </div> */}
                {/* (------------------ Stock div ------------------) */}
                {/*--------------------------------  Color Select -------------------------------- */}

                {/* <div dir="rtl" className="color-select-div mt-2">
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
                                <option value={onecolor.id} key={onecolor.id}>
                                    {onecolor.name}
                                </option>
                            ))}
                    </select>
                </div> */}
                {/*-------------------------------- Color Select -------------------------------- */}

                {/* ------------------------------- Size Select---------------------------------- */}
                {/* <div dir="rtl" className="size-select-div">
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
                </div> */}
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
                                <option value={onevolume.id} key={onevolume.id}>
                                    {onevolume.name}
                                </option>
                            ))}
                    </select>
                </div> */}
                {/* ------------------------------- Size Select---------------------------------- */}

                {/* ------------------------------- add stock,size,color button ---------------------------------- */}
                {/* <button
                    onClick={() => addStkSizClr(procutInfo)}
                    className="bg-blue-600 rounded-md p-2 my-3 text-white"
                >
                    إضافة
                </button> */}
                {/* ------------------------------- add stock,size,color button ---------------------------------- */}
            </div>

            {/* <div>{ReactHtmlParser(procutInfo.description)}</div> */}

            {/* {!isUpateProduct ? (
                <button
                    onClick={updateTraderProductModal}
                    className="bg-yellow-200 rounded-md p-2 my-3 text-black"
                >
                    تعديل معلومات المنتج
                </button>
            ) : (
                <button
                    onClick={() => setIsUpateProduct(!isUpateProduct)}
                    className=" bg-red-400  rounded-md p-2 my-3 text-white"
                >
                    الغاء تعديل المنتج
                </button>
            )}
            {isUpateProduct && (
                <UpdateTraderProductInputs traderProductInfo={procutInfo} />
            )} */}
        </div>
    );
};

export default TraderProductDetails;
