import axios from "axios";
import React, { useEffect, useState } from "react";
import { productsInWishlistNumber } from "../../Redux/countInCartSlice";

import { AiTwotoneHeart } from "react-icons/ai";
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

    const [imgsize, setimgsize] = useState("sm");

    const [discountValue, setDiscountValue] = useState("");

    const [priceAfterdiscount, setpriceAfterdiscount] = useState("");

    useEffect(() => {
        let theDiscountValue = (product.discount * product.sale_price) / 100; // ما تم خصمه
        setDiscountValue(theDiscountValue);

        let priceAfterDiscount = product.sale_price - theDiscountValue; // السعر بعد الخصم
        setpriceAfterdiscount(priceAfterDiscount);
    }, []);

    const dispatch = useDispatch();

    const navigate = useNavigate();

    // const getWishlistProductsCount = async () => {
    //     let getToken = JSON.parse(localStorage.getItem("clTk"));
    //     try {
    //         const res = await axios.get(
    //             `${process.env.MIX_APP_URL}/api/wishlists/`,
    //             {
    //                 headers: { Authorization: `Bearer ${getToken}` },
    //             }
    //         );
    //         let wishlistCount = res.data.data.length;
    //         dispatch(productsInWishlistNumber(wishlistCount));
    //     } catch (er) {
    //         console.log(er);
    //     }
    // };

    // console.log(window.innerWidth);
    // if (window.innerWidth < 500) {
    //     setimgsize('sm')
    // }

    return (
        <SwiperSlide
            key={product.id}
            dir={`rtl`}
            className="swiper-slide swiper-deals p-1 rounded-md relative"
            style={{
                backgroundColor: "#fff",
            }}
        >
            <div
                className="deals-porduct-container flex flex-col justify-between items-center"
                style={{ minHeight: "350px" }}
            >
                <Link
                    className="bg-slate-300 rounded-md"
                    to={`/products/product/${product.id}`}
                >
                    <div
                        className="product-img"
                        style={{
                            width: "200px",
                            height: "200px",
                        }}
                    >
                        <img
                            className="w-full h-full "
                            src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/sm/${product?.itemImages[0]?.img}`}
                            alt="لا يوجد صورة"
                        />
                    </div>
                </Link>
                {/* {product.discount > 0 && (
          <div className="discount-percent-div absolute p-1 font-semibold rounded-md bg-slate-100 opacity-4 text-red-500">
            {product.discount}%
          </div>
        )} */}

                <h5
                    className="overflow-hidden text-center text-ellipsis text-xl"
                    style={{ width: "97%" }}
                >
                    {product.name}
                </h5>

                {product.discount > 0 ? (
                    <>
                        <small
                            className="linethorugh relative"
                            // style={{
                            //     textDecorationColor: "red",
                            //     textDecorationLine: "line-through",
                            // }}
                        >
                            السعر: {product.sale_price} {"جنية "}
                        </small>
                        <h5 className="font-semibold sale-price-after-discount">
                            السعر: {priceAfterdiscount} {"جنية "}
                        </h5>
                    </>
                ) : (
                    <h5 className="font-semibold sale-price">
                        السعر: {product.sale_price} {"جنية "}
                    </h5>
                )}

                {product.discount > 0 && (
                    <small>
                        {" "}
                        وفر {discountValue} {"جنية "}
                    </small>
                )}

                {/* <div className="rate-div flex gap-2 my-3">
                        {Array.from(Array(product.allRates).keys()).map((star) => (
                            <AiTwotoneStar
                                key={star}
                                className="text-md text-amber-300"
                            />
                        ))}
                </div> */}

                <Link
                    className="details block font-bold cursor-pointer border-zinc-400 border-b-2 p-2 rounded-md bg-slate-200"
                    to={`/products/product/${product.id}`}
                >
                    تفاصيل
                </Link>
            </div>
        </SwiperSlide>
    );
};

export default OneDealsProduct;
