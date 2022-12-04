import React, { useState } from "react";
import { useDispatch } from "react-redux";
import { useNavigate } from "react-router-dom";

import heart from "./heart.gif";
import { MdOutlineCompareArrows } from "react-icons/md";
import { AiOutlineStar, AiTwotoneHeart } from "react-icons/ai";
import axios from "axios";
// import { productsInWishlistNumber } from "../../Redux/countInCartSlice";
import { productsInWishlistNumber } from "../../../Redux/countInCartSlice";

const OneTypeItemProduct = ({ oneItem, fetchAgain }) => {
    const [wishlistBtn, setWishlistBtn] = useState(false);

    const navigate = useNavigate();

    const dispatch = useDispatch();

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

    const saveToWishList = (product) => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken) {
            setWishlistBtn(true);
            axios
                .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    try {
                        await axios
                            .post(
                                `${process.env.MIX_APP_URL}/api/wishlists`,
                                {
                                    item_id: product,
                                },
                                {
                                    headers: {
                                        Authorization: `Bearer ${getToken}`,
                                    },
                                }
                            )
                            .then(async (resp) => {
                                setWishlistBtn(false);
                                console.log(resp);
                                fetchAgain();
                                getWishlistProductsCount();
                            });
                    } catch (er) {
                        console.log(er);
                    }
                });
        } else {
            navigate("/clientLogin");
        }
    };
    const goToDetails = (item) => {
        console.log(item);
        navigate(`/products/product/${item.id}`);
    };

    console.log(oneItem);

    return (
        <div
            key={oneItem.id}
            className="one-product-div p-3 shadow-lg rounded-lg relative"
            dir="rtl"
            style={{ width: "250px" }}
        >
            <div
                className="product-img "
                style={{
                    height: "250px",
                }}
            >
                <img
                    className=" mx-auto"
                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/${oneItem?.itemImages[0]?.img}`}
                    alt="لا يوجد صوره"
                />
            </div>
            <h5 className="mt-2">اسم المنتج:{oneItem.name}</h5>
            <h5 className="mt-2">السعر: {oneItem.sale_price}</h5>
            <h5 className="mt-2">{oneItem?.available}</h5>
            <div className="stock-count-div">
                <div>عدد القطع المتوفرة: {oneItem?.stock}</div>
            </div>
            <h5 className="rate-div flex mt-2 gap-2">
                <span className="">
                    <AiOutlineStar />
                </span>
                <span className="">
                    <AiOutlineStar />
                </span>
                <span className="">
                    <AiOutlineStar />
                </span>
            </h5>
            <div className="wichlist-product absolute top-0 right-0 p-2 rounded-md">
                <span className="mb-4 hover:text-red-600">
                    {!wishlistBtn ? (
                        <AiTwotoneHeart
                            onClick={() => saveToWishList(oneItem.id)}
                            className={`cursor-pointer ${
                                oneItem.wishlists == "true" && "text-red-500"
                            }`}
                        />
                    ) : (
                        <img className="w-5 h-5" src={heart} alt="" />
                    )}
                </span>
                <span className="my-3 py-3 ">
                    <MdOutlineCompareArrows className="cursor-pointer text-lg mt-3 hover:text-orange-400" />
                </span>
            </div>
            <div
                onClick={() => goToDetails(oneItem)}
                className="details font-bold cursor-pointer mt-2 border-zinc-400 border-b-2 p-2 rounded-md bg-slate-200"
            >
                تفاصيل المنتج
            </div>
        </div>
    );
};

export default OneTypeItemProduct;
