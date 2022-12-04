import React, { useEffect } from "react";

import "./homeContent.css";
import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";
import SliderOne from "../Sliderone/SliderOne";
import Deals from "../DealsPage/Deals";
import MostRecent from "../MostRecent/MostRecent";
import HighAdvertisement from "../advertisements/HighAdvertisement";
import LatestUpdatedProducts from "../LatestProducts/LatestUpdatedProducts";
import TowAddvertisments from "../advertisements/TowAddvertisments";
import { useDispatch } from "react-redux";
import { productsInWishlistNumber } from "../../Redux/countInCartSlice";
import axios from "axios";

const HomeContent = () => {
    const dispatch = useDispatch();

    useEffect(() => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken) {
            const getWishlistProductsCount = async () => {
                try {
                    const res = await axios.get(
                        `${process.env.MIX_APP_URL}/api/wishlists/`,
                        {
                            // cancelRequest: cancelRequest.token,
                            headers: { Authorization: `Bearer ${getToken}` },
                        }
                    );
                    let wishlistCount = res.data.data.length;
                    dispatch(productsInWishlistNumber(wishlistCount));
                    console.log(res);
                } catch (er) {
                    console.log(er);
                }
            };
            getWishlistProductsCount();
        } else {
            dispatch(productsInWishlistNumber(0));
        }
    }, []);
    return (
        <div className="p-1" style={{ backgroundColro: "#999" }}>
            <SliderOne />
            <Deals />
            <HighAdvertisement />
            <LatestUpdatedProducts />
            <TowAddvertisments />
            {/* <MostRecent /> */}
        </div>
    );
};

export default HomeContent;
