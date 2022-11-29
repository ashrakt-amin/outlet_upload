import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, Outlet, useParams } from "react-router-dom";
import VendorProducts from "../AdminPages/Vendors/VendorProducts";

const OneVendor = () => {
    const { id } = useParams();

    const [traderInfo, setTraderInfo] = useState({});

    console.log(id);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        console.log(id);
        const getOneVendor = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/traders/${id}`,
                    {
                        headers: { Authorization: `Bearer ${getToken}` },
                    }
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
    }, []);

    console.log(traderInfo);

    return (
        <div className="p-4" dir="rtl">
            <h1 className="text-center font-bold my-3 text-xl">
                معلومات التاجر
            </h1>
            <div className="tarder-info-grid grid lg:grid-cols-4 md:grid-cols-2 gap-4 p-3">
                <h3 className="bg-blue-400 p-2 rounded-md">
                    الاسم الاول: {traderInfo.f_name}
                </h3>
                <h3 className="bg-blue-400 p-2 rounded-md">
                    الاسم الثانى: {traderInfo.m_name}
                </h3>
                <h3 className="bg-blue-400 p-2 rounded-md">
                    الاسم الثالث: {traderInfo.l_name}
                </h3>
                <h3 className="bg-blue-400 p-2 rounded-md">
                    العمر: {traderInfo.age}
                </h3>
                <h3 className="bg-blue-400 p-2 rounded-md">
                    الهاتف {traderInfo.phone}
                </h3>
                <h3 className="bg-blue-400 p-2 rounded-md">
                    الايميل {traderInfo.email}
                </h3>
                <h3 className="bg-blue-400 p-2 rounded-md">
                    التخصص: {traderInfo.activity}
                </h3>
                {/* <h3>{traderInfo.}</h3> */}
            </div>
            <VendorProducts vendorProductArray={traderInfo} />
            {/* <Link to={`/dachboard/vendors/${traderInfo.id}/vendorProducts`}>
                المنتجات الخاصه بالتاجر
            </Link>
            <Outlet /> */}
        </div>
    );
};

export default OneVendor;
