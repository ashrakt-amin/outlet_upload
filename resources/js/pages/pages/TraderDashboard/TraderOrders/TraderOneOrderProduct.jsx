import axios from "axios";
import React, { useState } from "react";

import { AiTwotoneStar } from "react-icons/ai";

const TraderOneOrderProduct = ({ oneProduct }) => {
    const deleteSingleItem = async (product) => {
        console.log(product);
        let traderTk = JSON.parse(localStorage.getItem("trTk"));
        console.log(traderTk);
        let resp = await axios.put(
            `http://127.0.0.1:8000/api/orderDetails/${product.id}`,
            {
                headers: {
                    Authorization: `Bearer ${traderTk}`,
                },
            }
        );
        console.log(resp);
    };

    return (
        <>
            <h5 className="mt-2">تصنيف المنتج:{oneProduct?.type?.name}</h5>
            <h5 className="mt-2">اسم المنتج:{oneProduct.name}</h5>
            <h5 className="mt-2">السعر: {oneProduct.sale_price}</h5>
            <h5 className="mt-2">{oneProduct?.available}</h5>
            <div className="stock-count-div">
                <div>عدد القطع المتوفرة: {oneProduct?.stock}</div>
            </div>
            <div className="rate-div flex gap-2 my-3">
                {Array.from(Array(oneProduct.allRates).keys()).map((star) => (
                    <AiTwotoneStar
                        key={star}
                        className="text-md text-amber-300"
                    />
                ))}
            </div>

            <div className="delete-single-item-in-order">
                <button
                    onClick={() => deleteSingleItem(oneProduct)}
                    className="p-1 text-white rounded-md bg-red-500"
                >
                    إلغاء المنتج من الطلبات
                </button>
            </div>
        </>
    );
};

export default TraderOneOrderProduct;
