import React, { useEffect, useState } from "react";
import FormControl from "@mui/material/FormControl";
import { MenuItem, Select } from "@mui/material";

import axios from "axios";
import { useDispatch } from "react-redux";
import { productsInCartNumber } from "../../Redux/countInCartSlice";

import { FiTrash2 } from "react-icons/fi";
import { AiTwotoneStar } from "react-icons/ai";

const OneCartItem = ({ cartItem, refetchFunc }) => {
    const dispatch = useDispatch();

    console.log(cartItem);

    const [amount, setAmount] = useState("اختر");
    const amounts = [
        { value: "اختر", label: "اختر" },
        { value: "1", label: "1" },
        { value: "2", label: "2" },
        { value: "3", label: "3" },
        { value: "4", label: "4" },
        { value: "5", label: "5" },
    ];

    const [deleteOneReload, setdeleteOneReload] = useState(false);

    const [deleteMsg, setDeleteMsg] = useState("");

    const [updateAmountMsg, setUpdateAmountMsg] = useState("");

    const [refetch, setRefetch] = useState(false);

    const deleteProduct = async (itemId) => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        axios.defaults.withCredentials = true;
        let itemid = itemId.id;
        await axios
            .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
            .then(async (res) => {
                setdeleteOneReload(true);
                try {
                    const res = await axios.delete(
                        `${process.env.MIX_APP_URL}/api/carts/${itemid}`,
                        {
                            headers: {
                                Authorization: `Bearer ${getToken}`,
                            },
                        }
                    );
                    setDeleteMsg(res.data.message);
                    refetchFunc();
                    setRefetch(!refetch);
                    setdeleteOneReload(false);
                    setTimeout(() => {
                        setDeleteMsg("");
                    }, 5000);
                } catch (er) {
                    setdeleteOneReload(false);
                    console.log(er);
                }
            });
    };

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        axios.defaults.withCredentials = true;
        const getCartProducts = async () => {
            let getToken = JSON.parse(localStorage.getItem("clTk"));
            await axios
                .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
                .then(async (res) => {
                    try {
                        const res = await axios.get(
                            `${process.env.MIX_APP_URL}/api/carts`,
                            {
                                headers: {
                                    Authorization: `Bearer ${getToken}`,
                                },
                            }
                        );
                        dispatch(productsInCartNumber(res.data.data.length));
                    } catch (er) {
                        console.log(er);
                    }
                });
        };
        getCartProducts();

        return () => {
            cancelRequest.cancel();
        };
    }, [refetch]);

    const handleChange = async (e, product) => {
        setAmount(e.target.value);
        const { color, size } = cartItem.colorSizeStock;

        const getToken = JSON.parse(localStorage.getItem("clTk"));

        const { item } = product;

        if (e.target.value != "اختر") {
            if (e.target.value !== product.quantity) {
                try {
                    const res = await axios.post(
                        `${process.env.MIX_APP_URL}/api/carts`,
                        {
                            quantity: e.target.value,
                            item_id: item.id,
                            color_id: color.id,
                            size_id: size.id,
                        },
                        {
                            headers: {
                                Authorization: `Bearer ${getToken}`,
                            },
                        }
                    );
                    console.log(res.data.message);
                    setUpdateAmountMsg(res.data.message);
                    refetchFunc();
                    setTimeout(() => {
                        setUpdateAmountMsg("");
                    }, 5000);
                } catch (er) {
                    console.log(er);
                    setUpdateAmountMsg(er.response.data.message);
                    refetchFunc();
                    setTimeout(() => {
                        setDeleteMsg("");
                    }, 5000);
                }
            }

            if (e.target.value == product.quantity) {
                console.log("yes equal");
            }
        }
    };

    return (
        <div
            key={cartItem.id}
            className="one-product-div p-3 shadow-lg rounded-lg relative"
            dir="rtl"
        >
            {deleteMsg.length > 0 && (
                <div className="fixed top-7 text-center w-full left-0 bg-green-400">
                    {deleteMsg}{" "}
                </div>
            )}
            {updateAmountMsg.length > 0 && (
                <div className="absolute top-0 text-center w-full left-0 bg-green-400">
                    {updateAmountMsg}{" "}
                </div>
            )}
            <div className="first-row-info flex justify-start">
                <div className="product-img" style={{ width: "150px" }}>
                    <img
                        // src={`https://www.pngall.com/wp-content/uploads/2016/04/Watch-PNG-Clipart.png`}
                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/${cartItem.item.itemImages[0].img}`}
                        alt=""
                    />
                </div>

                <div className="p-1">
                    <h1> إسم المنتج: {cartItem.item.name}</h1>
                    <h1> سعر المنتج: {cartItem.item.sale_price} جنية</h1>
                    <h1> عدد المنتج المطلوب {cartItem?.quantity} </h1>
                    <h1>
                        {" "}
                        الخصم
                        {cartItem.item.discount == null
                            ? ":لا يوجد"
                            : cartItem.item.discount}
                    </h1>
                    <div className="rate-div flex gap-2 my-3">
                        {Array.from(Array(cartItem.item.allRates).keys()).map(
                            (star) => (
                                <AiTwotoneStar
                                    key={star}
                                    className="text-md text-amber-300"
                                />
                            )
                        )}
                    </div>
                </div>
            </div>
            <div className="add-to-cart flex justify-between items-end py-5 px-2">
                <FormControl sx={{ m: 1, minWidth: 120 }}>
                    <h1>تعديل الكمية</h1>
                    <Select
                        value={amount}
                        onChange={(e) => handleChange(e, cartItem)}
                        displayEmpty
                        inputProps={{ "aria-label": "Without label" }}
                    >
                        {amounts.map((oneAmount) => (
                            <MenuItem
                                key={oneAmount.value}
                                value={oneAmount.value}
                            >
                                {oneAmount.label}
                            </MenuItem>
                        ))}
                    </Select>
                </FormControl>

                {deleteOneReload ? (
                    "يتم المسح"
                ) : (
                    <button
                        className="shadow-md flex items-center justify-center text-xs rounded-md text-red-500 my-2 p-1 font-thin"
                        onClick={() => deleteProduct(cartItem)}
                    >
                        <span>إزالة</span> <FiTrash2 />
                    </button>
                )}
            </div>
        </div>
    );
};

export default OneCartItem;
