import axios from "axios";
import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const TraderOrders = () => {
    const [allItems, setAllItems] = useState([]);

    const redirect = useNavigate();

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let traderTk = JSON.parse(localStorage.getItem("trTk"));
        const getItems = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/traders/trader/`,
                    {
                        headers: {
                            Authorization: `Bearer ${traderTk}`,
                        },
                    }
                );
                setAllItems(res.data.data[0].items);
                console.log(res.data.data[0].items);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getItems();
        return () => {
            cancelRequest.cancel();
        };
    }, []);

    const showProduct = (pr) => {
        // console.log(pr);
        redirect(`/trader/dachboard/traderProduct/${pr.id}`);
    };

    // console.log(item.itemImages[0]);

    return (
        <div className="all-products-div grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 ">
            {!allItems && <div>لا يوجد منتجات</div>}
            {allItems &&
                allItems.map((item) => (
                    <div
                        onClick={() => showProduct(item)}
                        key={item.id}
                        className="mt-5 w-fit shadow-md p-1 rounded-sm cursor-pointer"
                    >
                        <div className="img-div w-52">
                            <img
                                className="w-full"
                                src={`http://127.0.0.1:8000/assets/images/uploads/items/${item?.itemImages[0]?.img}`}
                                alt="بره المنتج الصورة ليست موجودة"
                            />
                        </div>
                        <div>{item.name}</div>
                        <div> كود المنتج {item.code}</div>
                        <div>تصفح المنتج</div>
                    </div>
                ))}
        </div>
    );
};

export default TraderOrders;
