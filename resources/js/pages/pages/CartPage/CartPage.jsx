import axios from "axios";
import React, { useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import { productsInCartNumber } from "../../Redux/countInCartSlice";
import { FiShoppingCart } from "react-icons/fi";

import Box from "@mui/material/Box";
import FormControl from "@mui/material/FormControl";
import NativeSelect from "@mui/material/NativeSelect";

import OneCartItem from "./OneCartItem";
import { useNavigate } from "react-router-dom";

import { getCartProducts } from "../../Redux/cartProducts";

const CartPage = () => {
    const dispatch = useDispatch();

    const [cartData, setCartData] = useState([]);

    const [auth, setAuth] = useState(false);

    const [deleteAllReload, setdeleteAllReload] = useState(false);

    const [orderMsg, setOrderMsg] = useState("");

    const [isAddress, setIsAddress] = useState(false);

    const [addressValue, setAddressValue] = useState("");

    const [cartTotalValue, setCartTotalValue] = useState("");

    const [cartTotalWitoutDiscount, setcartTotalWitoutDiscount] = useState("");

    const [deleteMsg, setDeleteMsg] = useState("");

    const [refetch, setRefetch] = useState(false);

    const [productAmount, setProductAmount] = useState(1);

    const navigate = useNavigate();

    useEffect(() => {
        // dispatch(getCartProducts());
        const cancelRequest = axios.CancelToken.source();
        axios.defaults.withCredentials = true;
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken) {
            const getCartProductsFunc = async () => {
                await axios
                    .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
                    .then(async (res) => {
                        try {
                            const res = await axios.get(
                                `http://127.0.0.1:8000/api/carts`,
                                {
                                    headers: {
                                        Authorization: `Bearer ${getToken}`,
                                    },
                                }
                            );
                            setCartData(res.data.data);
                            console.log(res.data.data);
                            dispatch(
                                productsInCartNumber(res.data.data.length)
                            );
                            if (res.data.data.length > 0) {
                                let moneyFunc = res.data.data
                                    .map((el) => {
                                        return el.quantity * el.item.sale_price;
                                    })
                                    .reduce((el, next) => el + next);
                                let discountAmount = res.data.data
                                    .map((el) => {
                                        if (el.item.discount == null) {
                                            return 0;
                                        } else {
                                            return el.item.discount;
                                        }
                                    })
                                    .reduce((el, next) => el + next);
                                // total withou discount
                                const totalWithoutDiscount = moneyFunc;
                                setcartTotalWitoutDiscount(
                                    totalWithoutDiscount
                                );
                                // total withou discount

                                let calcCartWithDiscount =
                                    moneyFunc - discountAmount;
                                setCartTotalValue(calcCartWithDiscount);
                            } else {
                                setCartTotalValue("");
                            }
                            setAuth(true);
                        } catch (er) {
                            console.log(er);
                        }
                    });
            };
            getCartProductsFunc();
        }

        return () => {
            cancelRequest.cancel();
        };
    }, [refetch, dispatch]);

    const refetchFunc = () => setRefetch(!refetch);

    //
    const deleteAllCart = async () => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        axios.defaults.withCredentials = true;
        if (getToken) {
            await axios
                .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    setdeleteAllReload(true);
                    try {
                        const res = await axios.get(
                            `http://127.0.0.1:8000/api/carts/destroyAll`,
                            {
                                headers: {
                                    Authorization: `Bearer ${getToken}`,
                                },
                            }
                        );
                        setDeleteMsg(res.data.message);
                        dispatch(productsInCartNumber(0));
                        setRefetch(!refetch);
                        setdeleteAllReload(false);
                        setTimeout(() => {
                            setDeleteMsg("");
                            navigate(`/client/wishList`);
                        }, 5000);
                    } catch (er) {
                        setdeleteAllReload(false);
                        console.log(er);
                    }
                });
        }
    };

    const sendToOrders = async () => {
        setIsAddress(!isAddress);
    };

    const sendToOrdersNow = async () => {
        if (addressValue.length > 5) {
            let getToken = JSON.parse(localStorage.getItem("clTk"));
            let moneyFunc = cartData
                .map((el) => {
                    return el.quantity * el.item.sale_price;
                })
                .reduce((el, next) => el + next);

            let orderList = cartData.map((el) => {
                let myObj = {
                    color_size_stock_id: "",
                    order_statu_id: "",
                    trader_id: "",
                    quantity: "",
                    discount: "",
                    item_price: "",
                };
                myObj.color_size_stock_id = el.colorSizeStock.id;
                myObj.trader_id = el.item.trader.id;
                myObj.quantity = el.quantity;
                myObj.discount = el.item.discount;
                myObj.item_price = el.item.sale_price;
                myObj.order_statu_id = 1;
                return myObj;
            });

            axios.defaults.withCredentials = true;
            await axios
                .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    try {
                        const res = await axios.post(
                            `http://127.0.0.1:8000/api/orders`,
                            {
                                finance_id: 1,
                                address: addressValue,
                                total: moneyFunc,
                                order_statu_id: 1,
                                order_details: orderList,
                            },
                            {
                                headers: {
                                    Authorization: `Bearer ${getToken}`,
                                },
                            }
                        );
                        setRefetch(!refetch);
                        setOrderMsg(res.data.message);
                        setTimeout(() => {
                            setOrderMsg("");
                        }, 5000);
                    } catch (er) {
                        console.log(er);
                    }
                });
        }
    };

    const payWay = (e) => {
        console.log(e.target.value);
    };

    return (
        <div dir="rtl" className="pb-16">
            {deleteMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-red-500">
                    {deleteMsg}
                </div>
            )}
            {orderMsg.length > 0 && (
                <div className="fixed top-32 z-50 p-2 text-white text-center w-full left-0 bg-green-500">
                    {orderMsg}
                </div>
            )}
            {auth && (
                <div className="grid grid-cols-1 p-2">
                    {cartData.length > 0 &&
                        cartData.map((cartItem) => (
                            <OneCartItem
                                key={cartItem.id}
                                cartItem={cartItem}
                                refetchFunc={refetchFunc}
                            />
                        ))}
                </div>
            )}

            {!cartData.length > 0 && (
                <div className="shadow-md p-2 rounded-md ">
                    لا يوجد منتجات في السلة
                </div>
            )}

            {cartData.length > 0 && (
                <>
                    <div className="text-sm w-fit my-3 flex gap-2 p-2 shadow-md rounded-md bg-white">
                        <span>الاجمالى بالخصم: {cartTotalValue}جنية</span>
                        <FiShoppingCart className="text-lg mx-2 text-dark" />
                    </div>

                    <div className="text-sm w-fit flex gap-2 p-2 shadow-md rounded-md bg-white">
                        <span>
                            الاجمالى بدون خصم: {cartTotalWitoutDiscount}جنية
                        </span>
                        <FiShoppingCart className="text-lg mx-2 text-dark" />
                    </div>
                </>
            )}

            <div className="complete-sale-btns flex flex-col items-start gap-3 p-2 ">
                {cartData.length > 0 && (
                    <>
                        {!isAddress && (
                            <button
                                onClick={sendToOrders}
                                className=" shadow-md p-2 rounded-md  bg-green-400 text-white"
                            >
                                إتمام الشراء
                            </button>
                        )}
                        {isAddress && (
                            <button
                                onClick={sendToOrders}
                                className=" shadow-md p-2 rounded-md bg-red-500 text-slate-200"
                            >
                                إلغاء الشراء
                            </button>
                        )}
                    </>
                )}
            </div>

            {isAddress && (
                <>
                    {cartData.length > 0 && (
                        <div className="address-input-div p-2">
                            <span>اكتب عنوانك</span>
                            <input
                                value={addressValue}
                                onChange={(e) =>
                                    setAddressValue(e.target.value)
                                }
                                type="text"
                                placeholder="العنوان"
                                className="py-2 px-3 border-2 border-slate-200 rounded-lg w-full outline-none font-serif"
                            />
                            <Box className="my-2" sx={{ width: "fit-content" }}>
                                <FormControl fullWidth>
                                    <NativeSelect
                                        onChange={payWay}
                                        defaultValue={30}
                                        inputProps={{
                                            name: "age",
                                            id: "uncontrolled-native",
                                        }}
                                    >
                                        <option value={"visa"}>Visa</option>
                                        <option value={"cash"}>Cash</option>
                                        <option value={"vodafone"}>
                                            Vodafone Cash
                                        </option>
                                    </NativeSelect>
                                </FormControl>
                            </Box>

                            <button
                                onClick={sendToOrdersNow}
                                className=" shadow-md p-2 rounded-md bg-green-500 mt-2 text-slate-200"
                            >
                                إتمام الشراء الان
                            </button>
                        </div>
                    )}
                </>
            )}
            {deleteAllReload ? (
                "ستم المسح"
            ) : (
                <>
                    {cartData.length > 0 && (
                        <button
                            onClick={deleteAllCart}
                            className=" shadow-md p-2 mr-3 rounded-md bg-red-500 text-slate-200 mt-3"
                        >
                            مسح الكل
                        </button>
                    )}
                </>
            )}
        </div>
    );
};

export default CartPage;
