import axios from "axios";
import React, { useCallback, useEffect } from "react";
import { useState } from "react";
import { useNavigate, useParams } from "react-router-dom";

import { AiTwotoneHeart } from "react-icons/ai";
import { FaWhatsapp, FaCartPlus } from "react-icons/fa";

import { MdOutlineCompareArrows } from "react-icons/md";

import heart from "./heart.gif";

import "./clintProductDetails.scss";
import { useDispatch } from "react-redux";

import imgsize from "./900-650.jpg";

import cartAnimation from "./cartanimation.gif";

import {
    productsInCartNumber,
    productsInWishlistNumber,
} from "../../../Redux/countInCartSlice";

import OneClintProduct from "../../OneCliendProductComponent/OneClintProduct";

const ClientProductDetails = () => {
    const { id } = useParams();
    const dispatch = useDispatch();

    const navigate = useNavigate();

    const [discountValue, setDiscountValue] = useState("");

    const [priceAfterdiscount, setpriceAfterdiscount] = useState("");

    const [singleProduct, setSingleProduct] = useState({});

    const [typeIdValue, setTypeIdValue] = useState("");

    const [imgNum, setImgNum] = useState(0);

    const [productImgs, setProductImgs] = useState([]);

    const [matchingProducts, setMatchingProducts] = useState([]);

    const [productAmount, setProductAmount] = useState(1);

    const [reloadBtn, setReloadBtn] = useState(false);

    const [productMsg, setProductMsg] = useState("");

    const redirect = useNavigate();

    const [updateCartCount, setUpdateCartCount] = useState(false);

    const [refetchAgain, setRefetchAgain] = useState(false);

    const [wishlistBtn, setWishlistBtn] = useState(false);

    const [isbuyProduct, setisbuyProduct] = useState(false);

    const [isbuyFromHome, setisbuyFromHome] = useState(false);

    const [traderLogo, setTraderLogo] = useState("");

    const [colorId, setColorId] = useState("");

    const [sizeId, setSizeId] = useState("");

    const [relaodstate, setrelaodstate] = useState(false);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken == null) {
            getToken = "";
        }

        const getSubCategoriesWihoutOuth = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/items/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${getToken}`,
                        },
                    }
                );
                setSingleProduct(res.data.data);
                let theDiscountValue =
                    (res.data.data.discount * res.data.data.sale_price) / 100; // ما تم خصمه
                setDiscountValue(theDiscountValue);

                let priceAfterDiscount =
                    res.data.data.sale_price - theDiscountValue; // السعر بعد الخصم
                setpriceAfterdiscount(priceAfterDiscount);
                if (res.data.data.trader?.logo == null) {
                    setTraderLogo(
                        "https://th.bing.com/th/id/OIP.OCfe-0Jyvn5SS8on4BacEAHaEc?pid=ImgDet&rs=1"
                    );
                } else {
                    setTraderLogo(
                        `${process.env.MIX_APP_URL}/assets/images/uploads/traders/${res.data.data.trader.logo}`
                    );
                }
                window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
                setProductImgs(res.data.data.itemImages);
                getMatchingProducts(res.data.data?.category?.id);
            } catch (er) {
                console.log(er);
            }
        };
        getSubCategoriesWihoutOuth();

        return () => {
            cancelRequest.cancel();
        };
    }, [id, relaodstate]);

    const nextImg = (indx) => {
        setImgNum(indx);
    };

    const getMatchingProducts = async (typeId) => {
        try {
            const res = await axios.get(
                `${process.env.MIX_APP_URL}/api/categories/${typeId}`
            );
            setMatchingProducts(res.data.data.items);
            // window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
        } catch (er) {
            console.log(er);
        }
    };

    // `}

    const refetch = () => {
        getMatchingProducts(singleProduct.category.id);
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

    const saveToWishList = (id) => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));

        if (getToken) {
            setWishlistBtn(true);
            axios
                .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    try {
                        await axios
                            .post(
                                `${process.env.MIX_APP_URL}/api/wishlists`,
                                {
                                    item_id: id,
                                },
                                {
                                    headers: {
                                        Authorization: `Bearer ${getToken}`,
                                    },
                                }
                            )
                            .then(async (resp) => {
                                setWishlistBtn(false);
                                setrelaodstate(!relaodstate);
                                getWishlistProductsCount();
                                console.log(resp);
                                // refetchFn();
                            });
                    } catch (er) {
                        console.log(er);
                    }
                });
        } else {
            navigate("/clientLogin");
        }
    };

    const buyProduct = () => {
        setisbuyProduct(!isbuyProduct);
    };

    const getitTohome = () => {
        setisbuyFromHome(!isbuyFromHome);
        document.cookie = "";
    };

    const getitFromShope = () => {};

    return (
        <div>
            {/* <div className="prodcut-details-container flex gap-3 p-1 flex-wrap pb-24"> */}
            <div className="prodcut-details-container flex flex-wrap gap-3 items-start relative p-1 pb-24">
                {isbuyFromHome && (
                    <div
                        dir="rtl"
                        className="bg-white shadow-md p-2 z-50 fixed top-0 flex justify-center items-center left-0 w-full h-full"
                    >
                        <div
                            className="buy-from-home-content flex flex-col p-1 bg-slate-300 rounded-md"
                            style={{ maxWidth: "500px" }}
                        >
                            <button
                                className="text-black border-2 border-black rounded-lg p-1"
                                onClick={getitTohome}
                            >
                                إغلاق
                            </button>
                            <h1 className="text-black">الاسم</h1>
                            <input
                                className="rounded-md "
                                type="text"
                                name="name"
                                id="name"
                            />
                            <h1 className="text-black">رقم التليفون</h1>
                            <input
                                className="rounded-md "
                                type="tel"
                                name="tel"
                                id="tel"
                            />
                            <h1 className="text-black">العنوان</h1>
                            <input
                                className="rounded-md "
                                type="text"
                                name="address"
                                id="address"
                            />
                            <h1 className="text-black">كود الخصم</h1>
                            <input
                                className="rounded-md "
                                type="text"
                                name="promocode"
                                id="promocode"
                            />
                            <button className="text-black mt-1 shadow-md p-1 bg-slate-200 rounded-md">
                                طلب الان
                            </button>
                        </div>
                    </div>
                )}

                {productMsg && (
                    <div
                        dir="rtl"
                        className="product-msg-div p-5 text-center z-50 fixed top-56 left-0 w-full bg-green-400"
                    >
                        {productMsg}
                    </div>
                )}

                <div className="imgs-product-div mx-auto">
                    <div className="product-name text-end text-xl mt-3 font-medium">
                        {singleProduct?.name}
                    </div>
                    <div
                        className="img-div mx-auto"
                        style={{ maxWidth: "650px" }}
                    >
                        <img
                            className=""
                            loading="lazy"
                            src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/lg/${productImgs[imgNum]?.img}`}
                            // src={imgsize}
                        />
                    </div>
                    <div className="all-imgs flex flex-wrap gap-4 p-3">
                        {productImgs &&
                            productImgs.map((img, i) => (
                                <div
                                    style={{
                                        width: "60px",
                                        height: "60px",
                                    }}
                                    onClick={() => nextImg(i)}
                                    key={img.id}
                                    className="cursor-pointer"
                                >
                                    <img
                                        loading="lazy"
                                        className="w-full h-full"
                                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/lg/${img.img}`}
                                        alt="لا يوجد صورة"
                                    />
                                </div>
                            ))}
                    </div>
                </div>

                {/* الاضافة للكارت  */}
                <div
                    className="product-details-div w-full relative pr-5 py-5 rounded-md px-2 gap-3 flex-1"
                    dir="rtl"
                >
                    <div
                        onClick={() => saveToWishList(singleProduct.id)}
                        className="wichlist-product p w-fit rounded-md cursor-pointer bg-slate-100 opacity-4"
                    >
                        <span className="mb-4  hover:text-red-600 flex items-center">
                            <span> اضف الى المفضلة</span>
                            {!wishlistBtn ? (
                                <AiTwotoneHeart
                                    className={`cursor-pointer ${
                                        singleProduct.wishlist == true &&
                                        "text-red-500"
                                    }`}
                                />
                            ) : (
                                <img className="w-5 h-5" src={heart} alt="" />
                            )}
                        </span>
                    </div>
                    {/* <span>اضف الى صفحة مقارنة المنتج</span> */}
                    {/* <span className="my-3 py-3 ">
                        <MdOutlineCompareArrows className="cursor-pointer text-lg mt-3 hover:text-orange-400" />
                    </span> */}

                    {singleProduct.discount > 0 ? (
                        <>
                            <small
                                className="linethorugh relative"
                                style={{
                                    textDecorationColor: "red",
                                    textDecorationLine: "line-through",
                                }}
                            >
                                السعر: {singleProduct.sale_price} {"جنية "}
                            </small>
                            <h5 className="font-semibold sale-price-after-discount">
                                السعر: {priceAfterdiscount} {"جنية "}
                            </h5>
                        </>
                    ) : (
                        <h5 className="font-semibold sale-price">
                            السعر: {singleProduct.sale_price} {"جنية "}
                        </h5>
                    )}

                    {singleProduct.discount > 0 && (
                        <small className="font-bold text-md">
                            {" "}
                            وفر {discountValue} {"جنية "}
                        </small>
                    )}

                    {/* <div className="rate-div flex gap-2 my-3">
                        {Array.from(Array(singleProduct?.allRates).keys()).map(
                            (star) => (
                                <AiTwotoneStar
                                    key={star}
                                    className="text-md text-amber-300"
                                />
                            )
                        )}
                    </div> */}

                    <div className="product-decsription-container mt-3">
                        <div className="font-bold text-md">وصف المنتج</div>
                        <div className="description-container">
                            {singleProduct?.description != 0 ? (
                                <div
                                    className="description-div p-4"
                                    dangerouslySetInnerHTML={{
                                        __html: singleProduct?.description,
                                    }}
                                />
                            ) : (
                                "لا يوجد وصف للمنتج"
                            )}
                        </div>
                    </div>

                    <div className="cart-btn whats-div-btn flex gap-2 cursor-pointer mt-2">
                        <span className="bg-green-500 text-md p-1 mx-1 rounded-lg text-white">
                            <FaWhatsapp />
                        </span>
                        <a
                            // href={`http://wa.me/+2001061670173`}
                            href={`http://wa.me/+2${singleProduct.trader?.phone}`}
                            target="_blank"
                            className="font-bold text-md hover:text-green-500"
                        >
                            إشترى من خلال الواتس اب
                        </a>{" "}
                    </div>

                    <div
                        onClick={getitTohome}
                        className="form-div cursor-pointer flex items-center gap-2 mt-2"
                    >
                        <span className="bg-red-500 text-md p-1 mx-1 rounded-lg text-white">
                            <FaCartPlus />
                        </span>
                        <span className="hover:text-red-500 font-bold text-md">
                            اشترى من خلال الموقع
                        </span>
                    </div>

                    {/* {isbuyProduct && (
                        <div>
                            <button
                                onClick={getitTohome}
                                className="p-1 m-1 bg-red-500 text-white rounded-md"
                            >
                                الاستلام من المنزل
                            </button>
                            <button
                                onClick={getitFromShope}
                                className="p-1 m-1 bg-red-500 text-white rounded-md"
                            >
                                الاستلام من المحل
                            </button>
                        </div>
                    )} */}

                    {/* <div className="add-to-cart relative flex gap-4 py-5 px-2">
                        {!reloadBtn ? (
                            <div
                                onClick={() => addToCart(singleProduct)}
                                className="cart-btn w-20 flex cursor-pointer justify-center items-center "
                            >
                                <span className="bg-red-500 text-md p-1 mx-1 rounded-lg text-white">
                                    <FaCartArrowDown />
                                </span>
                                <span className="font-bold text-md hover:text-red-500 ">
                                    إشترى
                                </span>{" "}
                            </div>
                        ) : (
                            <span className="w-7 h-7">
                                <img src={cartAnimation} alt="" />
                            </span>
                        )}
                        <div className="product-counter flex justify-between items-center  w-48 rounded-md ">
                            <button
                                onClick={() => increase()}
                                className="px-2 shadow-md hover:bg-green-400 hover:text-white rounded-md  "
                            >
                                +
                            </button>
                            <div className="product-count-num">
                                {productAmount}
                            </div>
                            <button
                                onClick={decrease}
                                className="px-2 shadow-md hover:text-white hover:bg-red-500"
                            >
                                -
                            </button>
                        </div>
                    </div> */}
                </div>
            </div>
            {/* المنتجات المشابهة لهذا المنج */}
            <div className="matched-products-button-div text-end font-bold text-xl p-3 rounded-md">
                منتجات مشابهة
            </div>
            {/* المنتجات المشابهة لهذا المنج */}

            <div className="matched-products-div grid grid-cols-2 p-2 pb-16 gap-4 my-4">
                {matchingProducts &&
                    matchingProducts.map((oneMatchProduct) => (
                        <OneClintProduct
                            key={oneMatchProduct.id}
                            product={oneMatchProduct}
                            refetch={refetch}
                        />
                    ))}
            </div>
        </div>
    );
};

export default ClientProductDetails;
