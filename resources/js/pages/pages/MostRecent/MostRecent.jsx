import React from "react";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";

import { Navigation, EffectFade } from "swiper";

import "swiper/swiper-bundle.min.css";
import "swiper/swiper.min.css";

const MostRecent = () => {
    const dealsArr = [
        "https://media.allure.com/photos/5c3cfcbcb44f422c9f00c67b/master/pass/Allure-Editors-Favorite-Cheap-Beauty-Products-Lede-3000-.jpg",
        "https://i.insider.com/61c371ce3bbcdd0012a06398?width=700",
        "https://timebusinessnews.com/wp-content/uploads/2019/10/Best-Tech-Products-You-Should-Have-In-2019-800x445.jpg",
        "https://i0.wp.com/www.brumpost.com/wp-content/uploads/2020/03/Best-Products-Wallpaper.jpg",
        "https://corporate.oriflame.com/about-oriflame/product-philosophy/-/media/2E279B139BD74617958EDE0360D641FC.ashx",
        "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRon49ZzSgg6DZr9tGlPW5cqHZo_F4Ss4qJYg&usqp=CAU",
    ];

    return (
        <div className="deals-parent">
            <h1 className="p-3 text-lg text-center text-white bg-slate-800 mt-6 mb-2">
                <span className="bg-red-500 font-bold px-1 rounded-md">
                    المنتجات الحديثة
                </span>
            </h1>
            <Swiper
                style={{ width: "80%" }}
                className="swiper-parent"
                modules={[Navigation, EffectFade]}
                navigation
                effect=""
                loop
                speed={800}
                slidesPerView={4}
                spaceBetween={20}
                breakpoints={{
                    // when window width is >= 640px
                    200: {
                        width: 340,
                        slidesPerView: 1,
                    },
                    640: {
                        width: 640,
                        slidesPerView: 4,
                    },
                    // when window width is >= 768px
                    768: {
                        width: 768,
                        slidesPerView: 4,
                    },
                }}
            >
                {dealsArr.map((el, i) => (
                    <SwiperSlide
                        key={i}
                        className="swiper-slide p-3 rounded-md"
                        style={{
                            width: "150px",
                            height: "300px",
                            backgroundColor: "#fff",
                        }}
                    >
                        <div
                            className="product-img "
                            style={{ width: "100%", height: "200px" }}
                        >
                            <img
                                className="w-full h-full "
                                src={`${el}`}
                                alt=""
                            />
                        </div>
                        <h5>Product name</h5>
                        <h5>Product price</h5>
                        <h5>Product product Categorry</h5>
                    </SwiperSlide>
                ))}
            </Swiper>
        </div>
    );
};

export default MostRecent;
