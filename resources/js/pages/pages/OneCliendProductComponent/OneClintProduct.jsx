import axios from "axios";
import React, { useEffect, useState } from "react";

import "./oneClientProductStyle.scss";
import heart from "./heart.gif";
import { MdOutlineCompareArrows } from "react-icons/md";
import { AiTwotoneStar, AiTwotoneHeart } from "react-icons/ai";

import { Link, useNavigate } from "react-router-dom";
import { productsInWishlistNumber } from "../../Redux/countInCartSlice";
import { useDispatch } from "react-redux";

const OneClintProduct = ({ product, refetch }) => {
    const navigate = useNavigate();

    const [discountValue, setDiscountValue] = useState("");

    const [priceAfterdiscount, setpriceAfterdiscount] = useState("");

    useEffect(() => {
        let theDiscountValue = (product.discount * product.sale_price) / 100; // ما تم خصمه
        setDiscountValue(theDiscountValue);

        let priceAfterDiscount = product.sale_price - theDiscountValue; // السعر بعد الخصم
        setpriceAfterdiscount(priceAfterDiscount);
    }, []);

    const dispatch = useDispatch();

    const goToDetails = (item) => {
        window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
        navigate(`/products/product/${item.id}`);
    };

    const getWishlistProductsCount = async () => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        try {
            const res = await axios.get(
                `${process.env.MIX_APP_URL}/api/wishlists/`,
                {
                    headers: { Authorization: `Bearer ${getToken}` },
                }
            );
            let wishlistCount = res.data.data.length;
            dispatch(productsInWishlistNumber(wishlistCount));
        } catch (er) {
            console.log(er);
        }
    };

    const [wishlistBtn, setWishlistBtn] = useState(false);
    const saveToWishList = async (product) => {
        console.log(product);
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken) {
            setWishlistBtn(true);
            try {
                await axios
                    .post(
                        `${process.env.MIX_APP_URL}/api/wishlists`,
                        {
                            item_id: product,
                        },
                        {
                            headers: {
                                Authorization: `Bearer ${getToken}`,
                            },
                        }
                    )
                    .then(async (resp) => {
                        console.log(resp);
                        setWishlistBtn(false);
                        getWishlistProductsCount();
                        refetch();
                    });
            } catch (er) {
                console.log(er);
            }
        } else {
            navigate("/clientLogin");
        }
    };

    return (
        <div
            className="relative one-client-product flex flex-col justify-start items-center gap-2 p-3 bg-white shadow-md rounded-md"
            dir={`rtl`}
            style={{ maxWidth: "300px", minHeight: "360px" }}
        >
            <Link className="rounded-md" to={`/products/product/${product.id}`}>
                <div
                    className="product-img"
                    style={{
                        width: "280",
                        maHeight: "230px",
                    }}
                >
                    <img
                        className="w-full"
                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/sm/${product?.itemImages[0]?.img}`}
                        alt=""
                    />
                </div>
            </Link>

            {product.discount > 0 && (
                <div className="discount-percent-div absolute top-0 left-0 p-1 font-semibold rounded-md bg-slate-100 opacity-4 text-red-500">
                    {product.discount}%
                </div>
            )}

            <h5
                className="text-ellipsis text-center text-xl whitespace-nowrap"
                style={{ width: "90%", overflow: "hidden" }}
            >
                {product.name}
            </h5>

            {product.discount > 0 ? (
                <>
                    <small
                        className="linethorugh relative"
                        // style={{
                        //     textDecorationColor: "red",
                        //     textDecorationLine: "line-through",
                        // }}
                    >
                        السعر: {product.sale_price} {"جنية "}
                    </small>
                    <h5 className="font-semibold sale-price-after-discount">
                        السعر: {priceAfterdiscount} {"جنية "}
                    </h5>
                </>
            ) : (
                <h5 className="font-semibold sale-price">
                    السعر: {product.sale_price} {"جنية "}
                </h5>
            )}

            {product.discount > 0 && (
                <p>
                    {" "}
                    وفر {discountValue} {"جنية "}
                </p>
            )}

            {/* <div className="rate-div flex gap-2 my-3">
                    {Array.from(Array(product.allRates).keys()).map((star) => (
                        <AiTwotoneStar
                            key={star}
                            className="text-md text-amber-300"
                        />
                    ))}
            </div>  */}

            {/* <div className="wichlist-product absolute top-0 right-0 p-2 rounded-md bg-slate-100 opacity-4">
                <span className="mb-4 hover:text-red-600">
                    {!wishlistBtn ? (
                        <AiTwotoneHeart
                            onClick={() => saveToWishList(product.id)}
                            className={`cursor-pointer ${
                                product.wishlist == true && "text-red-500"
                            }`}
                        />
                    ) : (
                        <img className="w-5 h-5" src={heart} alt="" />
                    )}
                </span>
                <span className="my-3 py-3 ">
                    <MdOutlineCompareArrows className="cursor-pointer text-lg mt-3 hover:text-orange-400" />
                </span>
            </div> */}
            <Link
                className="details block font-bold cursor-pointer border-zinc-400 border-b-2 p-2 rounded-md bg-slate-200"
                to={`/products/product/${product.id}`}
            >
                تفاصيل
            </Link>
        </div>
    );
};

export default OneClintProduct;
