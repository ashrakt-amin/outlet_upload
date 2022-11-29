import React from "react";

import "./homeContent.css";
import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";
import SliderOne from "../Sliderone/SliderOne";
import Deals from "../DealsPage/Deals";
import MostRecent from "../MostRecent/MostRecent";
import HighAdvertisement from "../advertisements/HighAdvertisement";
import LatestUpdatedProducts from "../LatestProducts/LatestUpdatedProducts";
import TowAddvertisments from "../advertisements/TowAddvertisments";

const HomeContent = () => {
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
