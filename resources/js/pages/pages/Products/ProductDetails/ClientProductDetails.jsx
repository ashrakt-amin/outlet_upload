import axios from "axios";
import React, { useCallback, useEffect } from "react";
import { useState } from "react";
import { useNavigate, useParams } from "react-router-dom";

import { AiTwotoneHeart } from "react-icons/ai";

import { MdOutlineCompareArrows } from "react-icons/md";

import heart from "./heart.gif";

import "./clintProductDetails.scss";
import { useDispatch } from "react-redux";

import cartAnimation from "./cartanimation.gif";

import { productsInCartNumber } from "../../../Redux/countInCartSlice";

import OneClintProduct from "../../OneCliendProductComponent/OneClintProduct";

const ClientProductDetails = () => {
    const { id } = useParams();
    const dispatch = useDispatch();

    const navigate = useNavigate();

    const [discountValue, setDiscountValue] = useState("");

    const [priceAfterdiscount, setpriceAfterdiscount] = useState("");

    const [singleProduct, setSingleProduct] = useState({});

    console.log(singleProduct);

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

    const [traderLogo, setTraderLogo] = useState("");

    const [colorId, setColorId] = useState("");
    const [sizeId, setSizeId] = useState("");

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
                if (res.data.data.trader.logo == null) {
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
                console.log(res.data.data.category);
                getMatchingProducts(res.data.data?.category?.id);
                console.log();
            } catch (er) {
                console.log(er);
            }
        };
        getSubCategoriesWihoutOuth();

        return () => {
            cancelRequest.cancel();
        };
    }, [id]);

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
    return (
        <div>
            {/* <div className="prodcut-details-container flex gap-3 p-1 flex-wrap pb-24"> */}
            <div className="prodcut-details-container relative p-1 pb-24">
                {productMsg && (
                    <div
                        dir="rtl"
                        className="product-msg-div p-5 text-center z-50 fixed top-56 left-0 w-full bg-green-400"
                    >
                        {productMsg}
                    </div>
                )}

                <div className="imgs-product-div mx-auto">
                    <div className="img-div">
                        <img
                            className=""
                            loading="lazy"
                            src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/${productImgs[imgNum]?.img}`}
                        />
                    </div>
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
                                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/${img.img}`}
                                    alt="لا يوجد صورة"
                                />
                            </div>
                        ))}
                </div>

                {/* الاضافة للكارت  */}
                <div
                    className="product-details-div relative py-5 rounded-md px-2 gap-3 bg-slate-200 flex-1"
                    dir="rtl"
                >
                    <div
                        onClick={() => saveToWishList(singleProduct.id)}
                        className="wichlist-product w-fit rounded-md cursor-pointer bg-slate-100 opacity-4"
                    >
                        <span className="mb-4  hover:text-red-600 flex items-center">
                            <span>الشراء لاحقا</span>
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
                    <span className="my-3 py-3 ">
                        {/* <span>اضف الى صفحة مقارنة المنتج</span> */}
                        <MdOutlineCompareArrows className="cursor-pointer text-lg mt-3 hover:text-orange-400" />
                    </span>

                    <div className="product-name text-lg mt-3">
                        اسم المنتج: {singleProduct?.name}{" "}
                    </div>

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

                    {/* <div className="units-count-div mt-3">
                        <div>عدد القطع المتوفرة: {singleProduct?.stock} 30</div>
                    </div> */}
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

                    {/* <div className="select-color-size-div">
                        <div className="color-select-client mt-3">
                            <h1>اختر اللون</h1>
                            <select
                                className="rounded-md cursor-pointer"
                                onChange={whatColor}
                                name="color"
                                id="color"
                            >
                                <option value={"0"}>لم تختر بعد</option>
                                {singleProduct.colors &&
                                    singleProduct.colors.map((onecolor) => (
                                        <option
                                            value={onecolor.id}
                                            key={onecolor.id}
                                        >
                                            {onecolor.name}
                                        </option>
                                    ))}
                            </select>
                        </div>
                        <div className="size-select-client mt-3">
                            <h1>اختر المقاس</h1>
                            <select
                                className="rounded-md cursor-pointer"
                                onChange={whatSize}
                                name="size"
                                id="size"
                            >
                                <option value={"0"}>لم تختر بعد</option>
                                {singleProduct.sizes &&
                                    singleProduct.sizes.map((onesize) => (
                                        <option
                                            value={onesize.id}
                                            key={onesize.id}
                                        >
                                            {onesize.name}
                                        </option>
                                    ))}
                            </select>
                        </div>
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

                {/* معلومات التاجر */}
                <div
                    dir="rtl"
                    className="trader-info-for-client text-center shadow-lg m-3 rounded-lg p-2"
                >
                    <div
                        className="trader-logo-for-client mx-auto"
                        style={{ width: "300px" }}
                    >
                        <img
                            // className="w-full"
                            src={`${traderLogo}`}
                            alt="لا يوجد صورة"
                        />
                    </div>
                    <h1> اسم التاجر: {singleProduct?.trader?.f_name}</h1>
                    <h1>هاتف التاجر: {singleProduct?.trader?.phone}</h1>
                    <h1>عنوان التاجر: {"المشاية بجوار الصياد"}</h1>
                </div>
                {/* معلومات التاجر */}
            </div>
            {/* المنتجات المشابهة لهذا المنج */}
            <div
                className="matched-products-button-div text-center text-white p-3 rounded-md"
                style={{ backgroundColor: "rgb(220, 26, 33)" }}
            >
                منتجات مشابهة
            </div>
            {/* المنتجات المشابهة لهذا المنج */}

            <div className="matched-products-div flex flex-wrap justify-center pb-16 gap-4 my-4">
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
