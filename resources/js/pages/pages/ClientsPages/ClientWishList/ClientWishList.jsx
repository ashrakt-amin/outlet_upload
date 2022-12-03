import axios from "axios";
import React, { useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import { productsInWishlistNumber } from "../../../Redux/countInCartSlice";
import OneWishlistProduct from "./OneWishlistProduct";

const ClientWichList = () => {
    const dispatch = useDispatch();

    const [auth, setAuth] = useState(false);

    const [refetch, setRefetch] = useState(false);

    const [delAllBtn, setDelAllBtn] = useState(true);

    const [wishlistArray, setWishlistArray] = useState([]);

    const [deleteMsg, setDeleteMsg] = useState("");

    const refetchFunc = () => setRefetch(!refetch);

    const getWishlistProductsCount = async () => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken) {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/wishlists/`,
                    {
                        headers: { Authorization: `Bearer ${getToken}` },
                    }
                );
                let wishlistCount = res.data.data.length;
                dispatch(productsInWishlistNumber(wishlistCount));
            } catch (er) {
                console.log(er);
            }
        }
    };

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken) {
            const getOneClient = async () => {
                axios.defaults.withCredentials = true;
                axios
                    .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
                    .then(async (res) => {
                        try {
                            await axios
                                .get(`http://127.0.0.1:8000/api/wishlists`, {
                                    headers: {
                                        Authorization: `Bearer ${getToken}`,
                                    },
                                })
                                .then(async (resp) => {
                                    setWishlistArray(resp.data.data);
                                    setAuth(true);
                                });
                        } catch (er) {
                            console.log(er);
                        }
                    });
            };
            getOneClient();
        }
        return () => {
            cancelRequest.cancel();
        };
    }, [refetch]);

    const deleteWichlists = async () => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        try {
            setDelAllBtn(false);
            let res = await axios.delete(
                `http://127.0.0.1:8000/api/wishlists/destroy`,
                { headers: { Authorization: `Bearer ${getToken}` } }
            );
            setDelAllBtn(true);
            getWishlistProductsCount();
            setRefetch(!refetch);
        } catch (er) {
            console.log(er);
        }
        // axios
        //     .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
        //     .then(async (res) => {
        //     });
    };

    return (
        <div dir="rtl" className="pb-16">
            {deleteMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-red-500">
                    {deleteMsg}
                </div>
            )}

            {auth && (
                <div className="gird-products flex flex-wrap justify-center pb-16 gap-4 my-4">
                    {wishlistArray.length > 0 &&
                        wishlistArray.map((wishlistproduct) => (
                            <OneWishlistProduct
                                key={wishlistproduct?.item?.id}
                                wishlistproduct={wishlistproduct}
                                refetchFunc={refetchFunc}
                            />
                        ))}
                </div>
            )}

            {!wishlistArray.length > 0 && (
                <div className="shadow-md p-2 rounded-md ">
                    لا يوجد منتجات في قائمة التمنيات
                </div>
            )}

            {wishlistArray.length > 0 && (
                <>
                    {delAllBtn ? (
                        <button
                            onClick={deleteWichlists}
                            className=" shadow-md p-2 mr-3 rounded-md bg-red-500 text-slate-200 mt-3"
                        >
                            مسح الكل
                        </button>
                    ) : (
                        "يتم مسح الكل"
                    )}
                </>
            )}
        </div>
    );
};

export default ClientWichList;
