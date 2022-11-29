import axios from "axios";
import React, { useEffect, useState } from "react";
import TraderOneOrder from "./TraderOneOrder";

const TraderOrders = () => {
    const [traderOrdersArray, settraderOrdersArray] = useState([]);

    const [refetchData, setRefetchData] = useState(false);

    useEffect(() => {
        let traderTk = JSON.parse(localStorage.getItem("trTk"));
        axios.defaults.withCredentials = true;
        const getTraderOrders = async () => {
            axios
                .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    let resp = await axios.get(
                        "http://127.0.0.1:8000/api/orderDetails/trader",
                        {
                            headers: {
                                Authorization: `Bearer ${traderTk}`,
                            },
                        }
                    );
                    settraderOrdersArray(resp.data.data);
                    console.log(resp.data.data);
                });
        };
        getTraderOrders();
    }, [refetchData]);

    const cancelAllOrder = async (onebigOrder) => {
        let getTraderToken = JSON.parse(localStorage.getItem("trTk"));

        try {
            let res = await axios.put(
                `http://127.0.0.1:8000/api/orderDetails/cancelAll/${onebigOrder.orderDetails[0].id}`,
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
            setRefetchData(!refetchData);
        } catch (er) {
            console.log(er);
        }
    };

    const fetchOrdersFunc = () => setRefetchData(!refetchData);

    return (
        <div className="order-details-trader">
            {traderOrdersArray &&
                traderOrdersArray.map((arr) =>
                    arr.map((one) => (
                        <div
                            className="p-2 bg-emerald-400 border-2 rounded-md "
                            key={one.id}
                        >
                            <h1 className="p-1 text-center text-black bg-white m-2">
                                حالة الاوردر: {one.order_statu.name}
                            </h1>

                            {one.order_statu.id != 0 && (
                                <button
                                    className="bg-red-500 text-white p-2 rounded-md text-center"
                                    onClick={() => cancelAllOrder(one)}
                                >
                                    الغاء الاوردر
                                </button>
                            )}
                            <div className="gap-5 flex flex-wrap">
                                {one.orderDetails.map((oneOrder) => (
                                    <div
                                        key={oneOrder.id}
                                        className="rounded-md"
                                    >
                                        <TraderOneOrder
                                            fetchOrders={fetchOrdersFunc}
                                            oneOrder={oneOrder}
                                        />
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))
                )}
        </div>
    );
};

export default TraderOrders;
