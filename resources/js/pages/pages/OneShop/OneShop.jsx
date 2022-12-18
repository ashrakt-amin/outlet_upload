import React from "react";
import { useState } from "react";
import { useEffect } from "react";
import { useParams } from "react-router-dom";
import OneClintProduct from "../OneCliendProductComponent/OneClintProduct";
import OneShopSlider from "../OneShopSlider/OneShopSlider";

const OneShop = () => {
    const { id } = useParams();

    const [oneshope, setoneshope] = useState({});

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();

        const getUnit = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/units/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setoneshope(res.data.data);
            } catch (er) {
                console.log(er);
            }
        };

        getUnit();
        return () => {
            cancelRequest.cancel();
        };
    }, []);

    console.log(oneshope);
    return (
        <div className="oneshope-container" dir="rtl">
            <h1 className="text-center p-2 m-3 text-lg font-bold shadow-md rounded-md">
                {" "}
                {oneshope.name}
            </h1>
            {oneshope.images && <OneShopSlider imgs={oneshope.images} />}

            {/* <div className="oneshop-imgs flex flex-wrap gap-5">

                {oneshope.images &&
                    oneshope.images.map((oneshopimg) => (
                        <div
                            key={oneshopimg.id}
                            style={{ maxWidth: "250px", maxHight: "250px" }}
                        >
                            <img
                                src={`${process.env.MIX_APP_URL}/assets/images/uploads/units/sm/${oneshopimg.img}`}
                                alt="لا يوجد صورة حتى الان"
                            />
                        </div>
                    ))}
            </div> */}

            <div className="shope-items grid grid-cols-2 gap-4">
                {oneshope?.items &&
                    oneshope?.items.map((oneItem) => (
                        <div key={oneItem.id} className="one-items-div">
                            <OneClintProduct product={oneItem} />
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default OneShop;
