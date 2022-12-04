import React, { useState } from "react";

import { FiTrash2 } from "react-icons/fi";
import { AiTwotoneStar } from "react-icons/ai";
import { Link } from "react-router-dom";
import { useDispatch } from "react-redux";

import "./clientwishlistStyle.scss";
import { productsInWishlistNumber } from "../../../Redux/countInCartSlice";
import axios from "axios";

const OneWishlistProduct = ({ wishlistproduct, refetchFunc }) => {
    const dispatch = useDispatch();

    const [deleteBtn, setDeleteBtn] = useState(false);

    const getWishlistProductsCount = async () => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        try {
            const res = await axios.get(
                `${process.env.MIX_APP_URL}/api/wishlists/`,
                {
                    headers: { Authorization: `Bearer ${getToken}` },
                }
            );
            let wishlistCount = res.data.data.length;
            dispatch(productsInWishlistNumber(wishlistCount));
        } catch (er) {
            console.log(er);
        }
    };

    const saveToWishList = (wishProduct) => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        let wishProductId = wishProduct?.item?.id;
        setDeleteBtn(true);
        if (getToken) {
            axios
                .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    try {
                        await axios
                            .post(
                                `${process.env.MIX_APP_URL}/api/wishlists`,
                                {
                                    item_id: wishProductId,
                                },
                                {
                                    headers: {
                                        Authorization: `Bearer ${getToken}`,
                                    },
                                }
                            )
                            .then(async (resp) => {
                                getWishlistProductsCount();
                                console.log(resp);
                                refetchFunc();
                                setDeleteBtn(true);
                            });
                    } catch (er) {
                        console.log(er);
                    }
                });
        } else {
            navigate("/clientLogin");
        }
    };

    return (
        <div
            className="one-product-div p-3 shadow-lg rounded-lg relative"
            dir="rtl"
            style={{ width: "250px" }}
        >
            {deleteBtn ? (
                <span>يتم الازالة</span>
            ) : (
                <button
                    className="shadow-md flex items-center justify-center text-xs rounded-md text-red-500 my-2 p-1 font-thin"
                    onClick={() => saveToWishList(wishlistproduct)}
                >
                    <span>إزالة</span>
                    <FiTrash2 />
                </button>
            )}
            <div
                className="product-img "
                style={{
                    width: "100%",
                    height: "200px",
                }}
            >
                <img
                    className="w-full h-full "
                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/${wishlistproduct.item?.itemImages[0]?.img}`}
                    alt=""
                />
            </div>
            <h5>إسم المنتج: {wishlistproduct?.item?.name}</h5>
            <h5>
                {wishlistproduct?.item?.sale_price}
                جنية
            </h5>
            <h5>
                العدد المتوفر:
                {wishlistproduct?.item?.stock}
            </h5>
            <div className="rate-div flex gap-2 my-3">
                {Array.from(Array(wishlistproduct?.item?.allRates).keys()).map(
                    (star) => (
                        <AiTwotoneStar
                            key={star}
                            className="text-md text-amber-300"
                        />
                    )
                )}
            </div>
            <div className="m-2">
                <Link
                    className="details font-bold cursor-pointer mt-2 border-zinc-400 border-b-2 p-2 rounded-md bg-slate-200"
                    to={`/products/product/${wishlistproduct?.item?.id}`}
                >
                    تفاصيل المنتج
                </Link>
            </div>
        </div>
    );
};

export default OneWishlistProduct;
