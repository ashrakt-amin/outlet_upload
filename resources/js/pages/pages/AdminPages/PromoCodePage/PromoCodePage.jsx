import axios from "axios";
import React from "react";
import { useState } from "react";

const PromoCodePage = () => {
    const [amountDiscount, setamountDiscount] = useState("");

    const [traderCode, settraderCode] = useState("");

    const [percentageDiscount, setpercentageDiscount] = useState("");

    const [startDate, setstartDate] = useState("");

    const [endDate, setendDate] = useState("");

    const [isDiscPercentage, setisDiscPercentage] = useState(false);

    const [isDiscAmount, setisDiscAmount] = useState(false);

    const cancelDiscAmount = () => {
        setisDiscAmount(!isDiscAmount);
        setamountDiscount("");
    };

    const cancelDiscPercantage = () => {
        setisDiscPercentage(!isDiscPercentage);
        setpercentageDiscount("");
    };

    const openDiscAmount = () => {
        setisDiscAmount(!isDiscAmount);
        setisDiscPercentage(false);
        setpercentageDiscount("");
    };

    const openDiscPercentage = () => {
        setisDiscPercentage(!isDiscPercentage);
        setisDiscAmount(false);
        setamountDiscount("");
    };

    const addPromoCode = async () => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        try {
            const res = await axios.post(
                `${process.env.MIX_APP_URL}/api/coupons`,
                {
                    trader_id: traderCode,
                    percentage_discount: percentageDiscount,
                    amount_discount: amountDiscount,
                    starting_date: startDate,
                    expiring_date: endDate,
                },
                {
                    headers: { Authorization: `Bearer ${userToken}` },
                }
            );
            console.log(res);
        } catch (er) {
            console.log(er);
        }
    };

    return (
        <div dir="rtl" className="p-2">
            <h1 className="text-center p-2 text-xl bg-slate-300 mb-4">
                البرومو كود
            </h1>
            <div className="promo-code-container flex flex-col justify-start flex-wrap gap-4 items-start">
                <div className="trader-code-div">
                    <h1 className="trader-code">كود التاجر</h1>
                    <input
                        className="rounded-md"
                        type="number"
                        onChange={(e) => setTraderCode(e.target.value)}
                    />
                </div>
                <button
                    onClick={openDiscAmount}
                    className="bg-green-500 p-1 rounded-md text-white"
                >
                    الخصم الجنية
                </button>
                {isDiscAmount && (
                    <div className="promo-code-amount bg-slate-300 rounded-md p-1">
                        <button
                            onClick={cancelDiscAmount}
                            className="bg-red-500 p-1 rounded-md text-white"
                        >
                            الغاء
                        </button>
                        <h1>قيمة الخصم</h1>
                        <input
                            className="rounded-md"
                            type="number"
                            value={amountDiscount}
                            onChange={(e) => setamountDiscount(e.target.value)}
                        />
                    </div>
                )}

                <button
                    onClick={openDiscPercentage}
                    className="bg-green-500 p-1 rounded-md text-white"
                >
                    الخصم بالنسبة
                </button>
                {isDiscPercentage && (
                    <div className="promo-code-percentage bg-slate-300 rounded-md p-1">
                        <button
                            onClick={cancelDiscPercantage}
                            className="bg-red-500 p-1 rounded-md text-white"
                        >
                            الغاء
                        </button>
                        <h1>نسبة الخصم</h1>
                        <input
                            className="rounded-md"
                            type="number"
                            value={percentageDiscount}
                            onChange={(e) =>
                                setpercentageDiscount(e.target.value)
                            }
                        />
                    </div>
                )}

                <div className="promo-code-date flex flex-wrap gap-3 items-start">
                    <div className="start-date-div">
                        <h1>تاريخ بداية الخصم</h1>
                        <input
                            className="rounded-md"
                            onChange={(e) => setstartDate(e.target.value)}
                            type="date"
                            value={startDate}
                        />
                    </div>
                    <div className="end-date-div">
                        <h1>مدة الخصم بالايام</h1>
                        <input
                            className="rounded-md"
                            onChange={(e) => setendDate(e.target.value)}
                            type="number"
                            value={endDate}
                        />
                    </div>
                </div>
                <div className="add-promo-code-btn">
                    <button
                        onClick={addPromoCode}
                        className="bg-green-500 p-2 rounded-md m-2 text-white"
                    >
                        اضف البرومو كود
                    </button>
                </div>
            </div>
        </div>
    );
};

export default PromoCodePage;
