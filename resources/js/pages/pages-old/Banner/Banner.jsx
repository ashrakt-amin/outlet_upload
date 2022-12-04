import React from "react";

const Banner = () => {
    const bannersAr = [
        "https://www.cardexpert.in/wp-content/uploads/2022/08/Weekly-offers.gif",
        "https://www.dbs.com/in/iwov-resources/media/images/promotions/welcome-offer-1404x630.jpg",
        "https://img.freepik.com/free-vector/special-offer-creative-sale-banner-design_1017-16284.jpg?1",
    ];

    return (
        <>
            <h2
                className="bg-gray-800 text-center my-3 text-lg text-white p-2 font-bold
            "
            >
                إعلانات
            </h2>
            <div className="banners-div justify-center gap-3 lg:flex lg:flex-row sm:flex-col">
                {bannersAr.map((el, i) => (
                    <div
                        key={i}
                        className="single-banner flex items-center"
                        style={{ maxWidth: "400px" }}
                    >
                        <img src={`${el}`} alt="" />
                    </div>
                ))}
            </div>
        </>
    );
};

export default Banner;
