import axios from "axios";
import React, { useState } from "react";

import { AiTwotoneStar } from "react-icons/ai";

const TraderOneOrder = ({ oneOrder, fetchOrders }) => {
    const deleteSingleOrderItem = async (oneOrdr) => {
        let getTraderToken = JSON.parse(localStorage.getItem("trTk"));
        console.log(oneOrdr.id);
        try {
            let res = await axios.put(
                `http://127.0.0.1:8000/api/orderDetails/cancel/${oneOrdr.id}`,
                {
                    order_statu_id: 0,
                },
                {
                    headers: {
                        Authorization: `Bearer ${getTraderToken}`,
                    },
                }
            );
            console.log(res);
            fetchOrders();
        } catch (er) {
            console.log(er);
        }
    };
    console.log(oneOrder);

    return (
        <div
            className="one-product-div bg-slate-50 h-fit p-3 shadow-lg rounded-lg relative"
            dir="rtl"
            style={{ width: "250px" }}
        >
            <h1 className="bg-green-200 p-1 rounded-md m-1 text-center text-black">
                {" "}
                حالة المنتج: {oneOrder.order_statu.name}
            </h1>

            <img
                className="mx-auto "
                src={`http://127.0.0.1:8000/assets/images/uploads/items/${oneOrder.item?.itemImages[0]?.img}`}
                alt="لا يوجد صورة"
            />
            <div className="product-img "></div>
            <h5 className="mt-2">تصنيف المنتج:{oneOrder.item?.type?.name}</h5>
            <h5 className="mt-2">اسم المنتج:{oneOrder.item.name}</h5>
            <h5 className="mt-2">السعر: {oneOrder.item.sale_price} جنية</h5>
            <h5 className="mt-2">{oneOrder.item?.available}</h5>
            <div className="stock-count-div">
                <div> الكمية المطلوبة : {oneOrder?.quantity}</div>
            </div>
            <div className="rate-div flex gap-2 my-3">
                {Array.from(Array(oneOrder.item.allRates).keys()).map(
                    (star) => (
                        <AiTwotoneStar
                            key={star}
                            className="text-md text-amber-300"
                        />
                    )
                )}
            </div>

            <div className="delete-single-item-in-order">
                {oneOrder.order_statu.id != 0 && (
                    <button
                        onClick={() => deleteSingleOrderItem(oneOrder)}
                        className="p-1 text-white rounded-md bg-red-500"
                    >
                        إلغاء المنتج من الطلبات
                    </button>
                )}
            </div>
            <div className="next-product-state">
                <h1 className="p-1 bg-slate-300 text-black m-3">
                    يرجى تحديث حالة المنتج
                </h1>

                {oneOrder.order_statu.id != 0 && (
                    <button
                        onClick={() =>
                            updateOrderProductState(
                                oneOrder.next_order_statu.id
                            )
                        }
                        className="p-1 text-white rounded-md bg-green-500"
                    >
                        {oneOrder.next_order_statu.name}
                    </button>
                )}
            </div>
        </div>
    );
};

export default TraderOneOrder;
