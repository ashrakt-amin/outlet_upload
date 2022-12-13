import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, Outlet, useParams } from "react-router-dom";
import AddProductsToTraders from "../AdminPages/Vendors/AddProductsToTraders";
import VendorProducts from "../AdminPages/Vendors/VendorProducts";

const OneVendor = () => {
    const { id } = useParams();

    const [traderInfo, setTraderInfo] = useState({});

    const [isAddingProduct, setIsAddingProduct] = useState(false);

    const [getInfoAgain, setgetInfoAgain] = useState(false);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        console.log(id);
        const getOneVendor = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/traders/${id}`
                    // {
                    //     headers: { Authorization: `Bearer ${getToken}` },
                    // }
                );
                setTraderInfo(res.data.data);
                console.log(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getOneVendor();
        return () => {
            cancelRequest.cancel();
        };
    }, [getInfoAgain]);

    console.log(traderInfo);

    const getInfoAgainFunc = () => setgetInfoAgain(!getInfoAgain);

    const toggleAdding = () => setIsAddingProduct(!isAddingProduct);

    return (
        <div className="p-4" dir="rtl">
            <h1 className="text-center font-bold my-3 text-xl">
                معلومات التاجر
            </h1>
            <h1 className="text-lg">صورة المحل</h1>
            <div className="trader-logo-div">
                <div
                    className="logo-container"
                    style={{ maxWidth: "300px", maxHeight: "300px" }}
                >
                    <img
                        className="w-full"
                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/traders/${traderInfo.logo}`}
                        alt=""
                    />
                </div>
            </div>
            <div className="tarder-info-grid grid lg:grid-cols-4 md:grid-cols-2 gap-4 p-3">
                <h3 className="bg-blue-500 p-2 rounded-md font-bold text-white">
                    الاسم الاول: {traderInfo.f_name}
                </h3>
                <h3 className="bg-blue-500 p-2 rounded-md font-bold text-white">
                    الاسم الثانى: {traderInfo.m_name}
                </h3>
                <h3 className="bg-blue-500 p-2 rounded-md font-bold text-white">
                    الاسم الثالث: {traderInfo.l_name}
                </h3>
                <h3 className="bg-blue-500 p-2 rounded-md font-bold text-white">
                    العمر: {traderInfo.age}
                </h3>
                <h3 className="bg-blue-500 p-2 rounded-md font-bold text-white">
                    الهاتف :{traderInfo.phone}
                </h3>
                <h3 className="bg-blue-500 p-2 rounded-md font-bold text-white">
                    الايميل :{traderInfo.email}
                </h3>
                <h3 className="bg-blue-500 p-2 rounded-md font-bold flex gap-3 flex-wrap">
                    انشطة التاجر:{" "}
                    {traderInfo.activities &&
                        traderInfo.activities.map((oneActivity) => (
                            <div
                                className="bg-white p-1 rounded-md"
                                key={oneActivity.id}
                            >
                                {oneActivity.name}
                            </div>
                        ))}
                    {traderInfo.activities && "لا يوجد"}
                </h3>
            </div>

            {isAddingProduct ? (
                <button
                    onClick={() => setIsAddingProduct(!isAddingProduct)}
                    className="bg-red-600 text-white rounded-md p-2 mt-5"
                >
                    الغاء إضافة منتج
                </button>
            ) : (
                <button
                    onClick={() => setIsAddingProduct(!isAddingProduct)}
                    className="bg-blue-600 text-white rounded-md p-2 mt-5"
                >
                    إضافة منتج
                </button>
            )}
            {isAddingProduct && (
                <AddProductsToTraders
                    toggleAdding={toggleAdding}
                    getInfoAgainFunc={getInfoAgainFunc}
                    traderInfo={traderInfo}
                />
            )}

            <VendorProducts vendorProductArray={traderInfo} />
        </div>
    );
};

export default OneVendor;
