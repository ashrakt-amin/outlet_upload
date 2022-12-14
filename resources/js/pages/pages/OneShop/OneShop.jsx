import React from "react";
import { useState } from "react";
import { useEffect } from "react";
import { useParams } from "react-router-dom";
import OneClintProduct from "../OneCliendProductComponent/OneClintProduct";

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

            <div className="shope-items flex flex-wrap gap-4">
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
