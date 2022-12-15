import React from "react";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";

import { Navigation, EffectFade, Autoplay } from "swiper";

// import "./slideronestyle.scss";
import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";

const OneShopSlider = ({ imgs }) => {
    console.log(imgs);

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
            speed={400}
            slidesPerView={1}
            loop
            style={{ maxHeight: "100vh" }}
        >
            {imgs.map((el) => (
                <SwiperSlide
                    key={el.id}
                    className="swiper-slide flex justify-center items-start relative"
                >
                    <picture>
                        <source
                            media="(min-width: 600px)"
                            srcSet={`${process.env.MIX_APP_URL}/assets/images/uploads/units/lg/${el.img}`}
                        />
                        <img
                            src={`${process.env.MIX_APP_URL}/assets/images/uploads/units/sm/${el.img}`}
                            alt="لا يوجد صورة حتى الان"
                        />
                    </picture>
                    {/* <img
                        className="object-cover h-full"
                        style={{ width: "100%" }}

                        
                        alt=""
                    /> */}
                </SwiperSlide>
            ))}
        </Swiper>
    );
};

export default OneShopSlider;
