import React from "react";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css"; 

import { Navigation, EffectFade, Autoplay } from "swiper";

import "./slideronestyle.scss";
import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";

import outletImg from './website.png'

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
                    className="swiper-slide flex justify-center items-start relative"
                >
                    <img
                        className="object-cover h-full"
                        style={{ width: "100%" }}
                        src={`${el}`}
                        alt=""
                    />
                    {/* <div className="nada-name absolute left-0 w-full top-0 opacity-80">
                        <div className="div bg-red-600 w-fit p-1">
                            <h1 className="text-white font-bold text-sm">Designed by</h1>
                            <h1 className="text-white font-bold text-sm">Engineer: Nada Ebrahim Rady</h1>
                            <marquee className='font-semibold shadow-md rounded-md bg-slate-200 text-dark border-2' direction='left' scrollamount="5">ندى إبراهيم راضى</marquee>
                        </div>
                    </div> */}
                </SwiperSlide>
            ))}
        </Swiper>
    );
};

export default SliderOne;
 