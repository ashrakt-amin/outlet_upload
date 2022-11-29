import axios from "axios";
import React, { useEffect, useState } from "react";

import "./clientOrders.scss";

import { AiTwotoneStar } from "react-icons/ai";
import { Link } from "react-router-dom";

const ClientOrders = () => {
    const [orderList, setOrderList] = useState([]);
    const [isClient, setIsClient] = useState(false);

    console.log(orderList);

    const [successMsg, setsuccessMsg] = useState("");

    const startArray = [
        { id: 1, value: 1 },
        { id: 2, value: 2 },
        { id: 3, value: 3 },
        { id: 4, value: 4 },
        { id: 5, value: 5 },
    ];

    const [refetch, setRefetch] = useState(false);

    //  Get Orders (Client)
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getOneClient = async () => {
            axios.defaults.withCredentials = true;
            let getToken = JSON.parse(localStorage.getItem("clTk"));
            axios
                .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    try {
                        await axios
                            .get(`http://127.0.0.1:8000/api/orders`, {
                                headers: {
                                    Authorization: `Bearer ${getToken}`,
                                },
                            })
                            .then(async (resp) => {
                                console.log(resp.data.data);
                                setOrderList(resp.data.data);
                                if (resp.status == "200") {
                                    setIsClient(true);
                                }
                                // if client login or has token get it and call api to get data
                            });
                    } catch (er) {
                        console.log(er);
                    }
                });
        };
        getOneClient();
        return () => {
            cancelRequest.cancel();
        };
    }, [refetch]);

    const ratingProduct = async (starNum, product) => {
        let getClientToken = JSON.parse(localStorage.getItem("clTk"));
        try {
            await axios
                .post(
                    `http://127.0.0.1:8000/api/rates`,
                    {
                        item_id: product.item.id,
                        rate_degree: starNum,
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${getClientToken}`,
                        },
                    }
                )
                .then(async (resp) => {
                    setsuccessMsg(resp.data.message);
                    setTimeout(() => {
                        setsuccessMsg("");
                    }, 3000);
                });
        } catch (er) {
            console.log(er);
        }
    };

    const deleteAllOrder = async (order) => {
        let getClientToken = JSON.parse(localStorage.getItem("clTk"));
        try {
            await axios
                .put(
                    `http://127.0.0.1:8000/api/orders/cancel/${order.id}`,
                    {
                        order_statu_id: 0,
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${getClientToken}`,
                        },
                    }
                )
                .then(async (resp) => {
                    console.log(resp.data);
                    setRefetch(!refetch);
                });
        } catch (er) {
            console.log(er);
        }
    };

    return (
        <div>
            {isClient && (
                <div className="order-container pb-20">
                    {orderList &&
                        orderList.map((item1) => (
                            <div
                                dir="rtl"
                                key={item1.id}
                                style={{ border: "2px solid #dc1a21" }}
                                className="order-item relative shadow-md p-2 m-3 rounded-md"
                            >
                                {successMsg.length > 0 && (
                                    <div className="absolute text-white p-1 top-1 z-50 text-center w-full left-0 bg-green-500">
                                        {successMsg}
                                    </div>
                                )}
                                {item1.orderStatu.id != "0" && (
                                    <button
                                        className="bg-red-500 p-1 rounded-md text-white"
                                        onClick={() => deleteAllOrder(item1)}
                                    >
                                        الغاء الطلب
                                    </button>
                                )}
                                <div className="addrss-status flex gap-2 p-1">
                                    <h1 className="shadow-md rounded-md p-1">
                                        {" "}
                                        تم الطلب على هذا العنوان :{" "}
                                        {item1.address}
                                    </h1>
                                    <h1 className="shadow-md rounded-md p-1">
                                        حالة الطلب : {item1.orderStatu.name}
                                    </h1>
                                </div>
                                <div className="order-items-container flex flex-wrap gap-5">
                                    {item1.orderDetails &&
                                        item1.orderDetails.map((item2) => (
                                            <div
                                                key={item2.id}
                                                className="border-2 rounded-md one-order-product p-2 m-2"
                                            >
                                                <h4 className="m-2">
                                                    {item2.alwaysItem?.name}
                                                </h4>
                                                <div
                                                    className="product-img m-2"
                                                    style={{ width: "200px" }}
                                                >
                                                    <img
                                                        // src={`https://www.pngall.com/wp-content/uploads/2016/04/Watch-PNG-Clipart.png`}
                                                        src={`http://127.0.0.1:8000/assets/images/uploads/items/${item2.item.itemImages[0].img}`}
                                                        alt=""
                                                    />
                                                </div>
                                                <h4 className="price m-2">
                                                    {item2.item.name}
                                                </h4>
                                                <h4 className="price m-2">
                                                    {item2.item.sale_price}
                                                    جنية{" "}
                                                </h4>

                                                {item2.orderStatu.id == 4 && (
                                                    <div className="rate-product-div m-2">
                                                        <h6>
                                                            يسعدنا تقييمك للمنتج
                                                        </h6>
                                                        <div className="stars-array flex gap-2 p-1">
                                                            {startArray.map(
                                                                (star) => (
                                                                    <div
                                                                        key={
                                                                            star.id
                                                                        }
                                                                        style={{
                                                                            width: "40px",
                                                                            height: "40px",
                                                                            borderRadius:
                                                                                "50%",
                                                                        }}
                                                                        onClick={() =>
                                                                            ratingProduct(
                                                                                star.id,
                                                                                item2
                                                                            )
                                                                        }
                                                                        className="star cursor-pointer flex flex-col items-center justify-start shadow-md"
                                                                    >
                                                                        <span className="text-sm">
                                                                            {
                                                                                star.value
                                                                            }
                                                                        </span>

                                                                        <AiTwotoneStar className="text-md" />
                                                                    </div>
                                                                )
                                                            )}
                                                        </div>
                                                    </div>
                                                )}
                                                <div className="go-to-product-details m-2">
                                                    <Link
                                                        className="bg-slate-300 px-2 rounded-md"
                                                        to={`/products/product/${item2.item.id}`}
                                                    >
                                                        تفاصيل المنتج
                                                    </Link>
                                                </div>
                                            </div>
                                        ))}
                                </div>
                            </div>
                        ))}
                </div>
            )}
            {orderList.length == 0 && (
                <h1 className="text-center p-2 bg-green-400 rounded-md text-white">
                    لا يوجد حتى الان
                </h1>
            )}
        </div>
    );
};

export default ClientOrders;
