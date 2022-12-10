import React, { useEffect, useState } from "react";

import "./latsetProductsStyle.scss";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";

import { Navigation, EffectFade } from "swiper";

import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";
import axios from "axios";
import OneLatestProduct from "./OneLatestProduct";

const LatestUpdatedProducts = () => {
    const [products, setProducts] = useState([]);

    const [refetch, setRefetch] = useState(false);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken == null) {
            getToken = "";
        }
        const getAuthItems = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/items/latest`,
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
        getAuthItems();

        return () => {
            cancelRequest.cancel();
        };
    }, [refetch]);

    const refetchFn = () => setRefetch(!refetch);

    return (
        <div className="latestproducts-parent bg-red-600 pb-2">
            <h1 className="p-3 text-lg text-white bg-red-600 text-end mt-6 mb-2">
                <span className="text-dark font-bold text-endfont-bold px-1 rounded-md">
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
                                    className="swiper-slide latestproducts-one-swiper-slide p-3 rounded-md"
                                    style={{
                                        minHeight: "300px",
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
