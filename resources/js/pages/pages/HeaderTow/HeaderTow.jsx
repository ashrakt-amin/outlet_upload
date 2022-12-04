import React, { useState, useEffect } from "react";
import { Route, Routes, useNavigate } from "react-router-dom";
import { useSelector, useDispatch } from "react-redux";
import axios from "axios";

import { AiOutlineArrowDown } from "react-icons/ai";

import "./headerTow.scss";

import HomeContent from "../HomeContent/HomeContent";
import Products from "../Products/Products";
import { saveProduct } from "../../Redux/productSlice";
import CartPage from "../CartPage/CartPage";
import ClientWishList from "../ClientsPages/ClientWishList/ClientWishList";
import ClientProductDetails from "../Products/ProductDetails/ClientProductDetails";
import Profile from "../Profile_Account/Profile";

import ClientOrders from "../ClientsPages/ClientOrders/ClientOrders";
import SingleTypeProduct from "../Products/SingleTypeProduct/SingleTypeProduct";
import TraderByConstruction from "../TraderByConstruction/TraderByConstruction";

const HeaderTow = () => {
    const navgiate = useNavigate();

    const [tradersName, setTradersName] = useState("");

    const [fixedNavTow, setFixedNavTow] = useState("");

    const [categoriesArr, setCategoriesArr] = useState([]);
    const [levels, setLevels] = useState([]);

    const handleChange = (event) => {
        setTradersName(event.target.value);
    };

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getCategories = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/categories`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setCategoriesArr(res.data.data);
                console.log(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getCategories();
        const getConstructions = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/levels`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setLevels(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getConstructions();
        return () => {
            cancelRequest.cancel();
        };
    }, []);

    const getSubCateg = (sub) => {
        navgiate(`products/${sub.id}`);
    };

    // window.onscroll = () => {
    //     if (fixedNavTow == "" && window.scrollY > 100) {
    //         setFixedNavTow("header-two-position");
    //     } else if (window.scrollY < 100) {
    //         setFixedNavTow("");
    //     }
    // };

    const traderByContstruction = () => {
        navgiate(`/traderByConstruction/1`);
    };

    return (
        <>
            <nav
                className={`flex text-white justify-center gap-4 header-tow p-1`}
                style={{ backgroundColor: "#fff" }}
            >
                <div className="traders">
                    <div className="dropdown-categories">
                        <button className="dropbtn-categories flex items-center  border-b-2 p-2 rounded-md">
                            <AiOutlineArrowDown />
                            <span>التجار</span>
                        </button>
                        <div className="dropdown-content-categories">
                            {levels &&
                                levels.map((level) => (
                                    <div
                                        className="hover:rounded-md"
                                        key={level.id}
                                        onClick={traderByContstruction}
                                    >
                                        تجار {level.name}
                                    </div>
                                ))}
                        </div>
                    </div>
                </div>
                <div className="categories">
                    <div className="dropdown-categories">
                        <button className="dropbtn-categories flex items-center  border-b-2 p-2 rounded-md">
                            <AiOutlineArrowDown /> <span>التصنيفات</span>
                        </button>
                        <div
                            dir="rtl"
                            className="dropdown-content-categories  relative"
                        >
                            {categoriesArr &&
                                categoriesArr.map((catg) => (
                                    <div
                                        key={catg.id}
                                        className="main-categ-name main-categ-dropbtn relative border-b-2 rounded-md"
                                    >
                                        {catg.name}
                                        <div className="subCategContent absolute hidden rounded-md">
                                            {catg.subCategories &&
                                                catg.subCategories.map(
                                                    (sub) => (
                                                        <div
                                                            onClick={() =>
                                                                getSubCateg(sub)
                                                            }
                                                            className=" text-black border-b-2"
                                                            key={sub.id}
                                                        >
                                                            {sub.name}
                                                        </div>
                                                    )
                                                )}
                                        </div>
                                    </div>
                                ))}
                        </div>
                    </div>
                </div>
            </nav>

            <Routes>
                <Route path="/" element={<HomeContent />} />
                <Route path="/products/:id/*" element={<Products />} />
                <Route
                    path="/products/product/:id"
                    element={<ClientProductDetails />}
                />
                <Route
                    path="/products/types/:id"
                    element={<SingleTypeProduct />}
                />
                <Route
                    path="/traderByConstruction/:id"
                    element={<TraderByConstruction />}
                />
                <Route path="/client/cart" element={<CartPage />} />
                <Route path="/client/wishList" element={<ClientWishList />} />
                <Route path="/account" element={<Profile />} />
                <Route path="/clientOrders" element={<ClientOrders />} />
            </Routes>
        </>
    );
};

export default HeaderTow;
