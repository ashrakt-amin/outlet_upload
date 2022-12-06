import React from "react";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css"; 

import { Navigation, EffectFade, Autoplay } from "swiper";

import "./slideronestyle.scss";
import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";

import outletImg from './outlet.jpeg'

const sliderContent = [
    "https://madina-center.com/wp-content/uploads/2022/10/new-web1-scaled.jpg",
    "https://madina-center.com/wp-content/uploads/2022/10/new-web-3-scaled.jpg",
    "https://madina-center.com/wp-content/uploads/2022/10/new-web1-scaled.jpg",
    outletImg
];

const SliderOne = () => {
    return (
        <Swiper
            className="swiper-parent slider-one my-4"
            modules={[Navigation, EffectFade, Autoplay]}
            autoplay={{
                delay: 2500,
                disableOnInteraction: false,
            }}
            navigation
            effect=""
            speed={800}
            slidesPerView={1}
            loop
            style={{ maxHeight: "100vh" }}
        >
            {sliderContent.map((el, i) => (
                <SwiperSlide
                    key={i}
                    className="swiper-slide flex justify-center items-start"
                >
                    <img
                        className="object-cover h-full"
                        style={{ width: "100%" }}
                        src={`${el}`}
                        alt=""
                    />
                </SwiperSlide>
            ))}
        </Swiper>
    );
};

export default SliderOne;
