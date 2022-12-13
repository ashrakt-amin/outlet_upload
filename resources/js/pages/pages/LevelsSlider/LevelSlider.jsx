import React from "react";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";

import { Navigation, EffectFade, Autoplay } from "swiper";

import "./slideronestyle.scss";
import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";

const LevelSlider = ({ imgs }) => {
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
            {imgs.map((el) => (
                <SwiperSlide
                    key={el.id}
                    className="swiper-slide flex justify-center items-start relative"
                >
                    <img
                        className="object-cover h-full"
                        style={{ width: "100%" }}
                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/levels/${el.img}`}
                        alt=""
                    />
                </SwiperSlide>
            ))}
        </Swiper>
    );
};

export default LevelSlider;
