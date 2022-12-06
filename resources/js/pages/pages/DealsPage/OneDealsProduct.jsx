import axios from "axios";
import React, { useState } from "react";
import { productsInWishlistNumber } from "../../Redux/countInCartSlice";

import { AiOutlineStar, AiTwotoneHeart, AiTwotoneStar } from "react-icons/ai";
import { MdOutlineCompareArrows } from "react-icons/md";
import heart from "./heart.gif";

import "./dealsStyle.scss";
import { SwiperSlide } from "swiper/react";

import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";
import "swiper/css";
import { Link, useNavigate } from "react-router-dom";
import { useDispatch } from "react-redux";
 
const OneDealsProduct = ({ product, refetchFn }) => {
    const [wishlistBtn, setWishlistBtn] = useState(false);

    console.log(product);

    const dispatch = useDispatch();

    const navigate = useNavigate();

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

    const saveToWishList = (id) => {
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
                                    item_id: id,
                                },
                                {
                                    headers: {
                                        Authorization: `Bearer ${getToken}`,
                                    },
                                }
                            )
                            .then(async (resp) => {
                                setWishlistBtn(false);
                                getWishlistProductsCount();
                                console.log(resp);
                                refetchFn();
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
        <div className="">
            <SwiperSlide
                key={product.id}
                dir={`rtl`}
                className="swiper-slide p-1 rounded-md"
                style={{
                    backgroundColor: "#fff",
                }}
            >
                <div
                    className="product-img max-sm:w-10/12"
                    style={{
                        width: "200",
                        height: "200px",
                    }}
                >
                    <img
                        className="w-full h-full "
                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/${product?.itemImages[0]?.img}`}
                        alt=""
                    />
                </div>
                <h5 className="whitespace-nowrap overflow-hidden text-ellipsis w-2.5" >{product.name}</h5>
                <h5>
                    العدد المتوفر:
                    30 {product.stock}
                </h5>
                <h5>  السعر: {1000}  </h5>
                <div className="rate-div flex gap-2 my-3">
                    {Array.from(Array(product.allRates).keys()).map((star) => (
                        <AiTwotoneStar
                            key={star}
                            className="text-md text-amber-300"
                        />
                    ))}
                </div>
                <div className="wichlist-product absolute top-0 right-0 p-2 rounded-md bg-slate-100 opacity-4">
                    <span className="mb-4 hover:text-red-600">
                        {!wishlistBtn ? (
                            <AiTwotoneHeart
                                onClick={() => saveToWishList(product.id)}
                                className={`cursor-pointer ${
                                    product.wishlist == true && "text-red-500"
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
                <Link
                    className="bg-slate-300 p-2 rounded-md"
                    to={`/products/product/${product.id}`}
                >
                    تفاصيل المنتج
                </Link>
            </SwiperSlide>
        </div>
    );
};

export default OneDealsProduct;
