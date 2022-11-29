import React, { useEffect, useState } from "react";
import { FiHeart } from "react-icons/fi";
import { MdOutlineCompareArrows } from "react-icons/md";
import { FaCartArrowDown } from "react-icons/fa";

// import "./dealsStyle.scss";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";

import { Navigation, EffectFade } from "swiper";

import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";
import axios from "axios";
import OneLatestProduct from "./OneLatestProduct";
import { Compare } from "@mui/icons-material";

const LatestUpdatedProducts = () => {
    const [products, setProducts] = useState([]);

    const [wishList, setWishList] = useState([]);

    const [wishlistBtn, setWishlistBtn] = useState(false);

    const [refetch, setRefetch] = useState(false);

    const [result, setResult] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        const getAuthItems = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/items/latest`,
                    {
                        headers: {
                            Authorization: `Bearer ${getToken}`,
                        },
                    }
                );
                setProducts(res.data.data);
                console.log(res.data);
            } catch (error) {
                console.warn(error.message);
            }
        };

        const getAuthItemsWithoutAuth = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/items/latest`
                );
                setProducts(res.data.data);
                console.log(res.data);
            } catch (error) {
                console.warn(error.message);
            }
        };

        if (localStorage.getItem("clTk")) {
            console.log("is logged");
            getAuthItems();
            //1 check client is true

            //2 get call auth function
        } else {
            getAuthItemsWithoutAuth();
        }

        return () => {
            cancelRequest.cancel();
        };
    }, [refetch]);

    // useEffect(() => {
    //     let newproducts = products;
    //     let wish = wishList;
    //     newproducts.forEach((product) => {
    //         for (let i = 0; i < wish.length; i++) {
    //             if (product.id == wish[i].item.id) {
    //                 product.wishlists = "yes";
    //             }
    //         }
    //     });
    //     setResult(newproducts);
    //     console.log(newproducts);
    // }, [products, wishList, refetch]);

    const refetchFn = () => setRefetch(!refetch);

    return (
        <div className="deals-parent">
            <h1 className="p-3 text-lg text-center text-white bg-slate-800 mt-6 mb-2">
                <span className="bg-red-500 font-bold px-1 rounded-md">
                    المضافة حديثاً
                </span>
            </h1>
            <Swiper
                style={{ width: "80%" }}
                className="swiper-parent"
                modules={[Navigation, EffectFade]}
                navigation
                loop
                effect=""
                speed={800}
                slidesPerView={4}
                spaceBetween={20}
                breakpoints={{
                    // when window width is >= 640px
                    200: {
                        width: 340,
                        slidesPerView: 2,
                    },
                    640: {
                        width: 640,
                        slidesPerView: 3,
                    },
                    // when window width is >= 768px
                    768: {
                        width: 768,
                        slidesPerView: 3,
                    },
                }}
            >
                {products &&
                    products.map((product) => (
                        <div key={product.id} className="">
                            <div key={product.id}>
                                <SwiperSlide
                                    key={product.id}
                                    dir={`rtl`}
                                    className="swiper-slide p-3 rounded-md"
                                    style={{
                                        minHeight: "400px",
                                        backgroundColor: "#fff",
                                    }}
                                >
                                    <OneLatestProduct
                                        refetchFn={refetchFn}
                                        product={product}
                                    />
                                </SwiperSlide>
                            </div>
                        </div>
                    ))}
            </Swiper>
        </div>
    );
};

export default LatestUpdatedProducts;
