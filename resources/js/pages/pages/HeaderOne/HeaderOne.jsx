import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import { FiHeart } from "react-icons/fi";
import { MdOutlineManageAccounts } from "react-icons/md";
import { AiOutlineArrowDown } from "react-icons/ai";
import { BiLogOut } from "react-icons/bi";

import { useDispatch, useSelector } from "react-redux";

import "./headerOne.scss";

import "./headerTow.scss";

import { useState, useRef, useEffect } from "react";
import React from "react";
import { productsInWishlistNumber } from "../../Redux/countInCartSlice";

const Header = () => {
    const searchInputRef = useRef();

    const [categoriesArr, setCategoriesArr] = useState([]);

    const [levels, setLevels] = useState([]);

    const [fixedNav, setFixedNav] = useState("");

    const dispatch = useDispatch();

    const count = useSelector((state) => state.numberInCart.productsInCart);

    const wichlistCount = useSelector(
        (state) => state.numberInWishlist.productsInWishlist
    );

    const navigate = useNavigate();

    const [searchIcon, setSearchIcon] = useState(true);

    const [closeSearch, setCloseSearch] = useState(false);

    const openSearch = () => {
        searchInputRef.current.classList.add("show-input");
        setCloseSearch(!closeSearch);
        setSearchIcon(!searchIcon);
    };

    const closeSerach = () => {
        searchInputRef.current.classList.remove("show-input");
        setCloseSearch(!closeSearch);
        setSearchIcon(!searchIcon);
    };

    window.onscroll = () => {
        if (fixedNav == "" && window.scrollY > 400) {
            setFixedNav("fixed-nav-one");
        } else if (window.scrollY < 100) {
            setFixedNav("");
        }
    };

    // --------------------- get Cart -----------------------
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));

        if (getToken == null) {
            getToken = "";
        }

        if (getToken) {
            const getWishlistProductsCount = async () => {
                try {
                    const res = await axios.get(
                        `${process.env.MIX_APP_URL}/api/wishlists/`,
                        {
                            cancelRequest: cancelRequest.token,
                            headers: { Authorization: `Bearer ${getToken}` },
                        }
                    );
                    let wishlistCount = res.data.data.length;
                    dispatch(productsInWishlistNumber(wishlistCount));
                } catch (er) {
                    console.log(err);
                }
            };
            getWishlistProductsCount();
        }
        const getCategories = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/categories`,
                    {
                        headers: {
                            Authorization: `Bearer ${getToken}`,
                        },
                    }
                );
                setCategoriesArr(res.data.data);
                console.log(res.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getCategories();
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/projects`
                    // {
                    //     headers: {
                    //         Authorization: `Bearer ${getToken}`,
                    //     },
                    // }
                );
                setLevels(res.data.data);
                console.log(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getLevels();
        return () => {
            cancelRequest.cancel();
        };
    }, []);
    // --------------------- get Cart -----------------------

    const goToCart = () => {
        if (localStorage.getItem("clTk")) {
            navigate("/client/cart");
        } else {
            navigate("/clientLogin");
        }
    };

    const getSubCateg = (sub) => {
        navigate(`products/${sub.id}`);
    };

    const traderByContstruction = (constructId) => {
        navigate(`/traderByConstruction/${constructId.id}`);
        // navigate(`/leveltrader/${constructId.id}`);
        console.log(constructId);
    };

    const logoutFunc = async () => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        axios.defaults.withCredentials = true;
        try {
            let res = await axios.post(
                `${process.env.MIX_APP_URL}/api/logout`,
                {},
                {
                    headers: { Authorization: `Bearer ${getToken}` },
                }
            );
            dispatch(productsInWishlistNumber(0));
            localStorage.removeItem("clTk");
            navigate("/");
        } catch (er) {
            console.log(er);
        }
    };

    // const wishlistProductsCount = async () => {
    //     let getToken = JSON.parse(localStorage.getItem("clTk"));
    //     try {
    //         const res = await axios.get(
    //             `${process.env.MIX_APP_URL}/api/wishlists/`,
    //             {
    //                 headers: { Authorization: `Bearer ${getToken}` },
    //             }
    //         );
    //         let wishlistCount = res.data.data.length;
    //         dispatch(productsInWishlistNumber(wishlistCount));
    //         console.log(res);
    //     } catch (er) {
    //         console.log(er);
    //     }
    // };

    const goToWishList = () => {
        if (localStorage.getItem("clTk")) {
            navigate("/client/wishList");
        } else {
            navigate("/clientLogin");
        }
    };

    const whatLevel = (e) => {
        console.log(e.levels);
        if (e.levels.length == 0) {
            console.log(e.levels);
            navigate("/allStreets");
        }
    };

    return (
        <>
            <nav
                dir="rtl"
                className={`flex flex-col ${fixedNav} header-one justify-between flex-wrap header`}
            >
                <div
                    dir="rtl"
                    className="search-first-nav-btns w-100 flex justify-between items-center flex-wrap px-3"
                >
                    {/* <div className="search-input-div relative">
                        {closeSearch && (
                            <AiFillCloseCircle
                                onClick={closeSerach}
                                className="text-lg close-search-icons text-white cursor-pointer absolute left-0 -top-4"
                            />
                        )}
                        <div className="search-input" ref={searchInputRef}>
                            <input
                                type="search"
                                className="rounded-lg w-full "
                            />
                            <button className="search-btn border-2 text-sm rounded-md p-1 text-white">
                               
                                بحث
                            </button>
                        </div>
                        {searchIcon && (
                            <button>
                                <BiSearchAlt
                                    onClick={openSearch}
                                    className="text-lg text-white hidden show-search-icon cursor-pointer"
                                />
                                
                            </button>
                        )}
                    </div> */}

                    <h2
                        className="text-xl text-gray-100 logo-div cursor-pointer"
                        style={{ width: "300px" }}
                        onClick={() => navigate("/")}
                    >
                        <img
                            className=""
                            src="https://madina-center.com/wp-content/uploads/2022/10/lastlogo.png"
                            alt=""
                        />
                    </h2>

                    <div className="flex gap-5 nav-mobile">
                        <button
                            onClick={goToWishList}
                            className="relative wichlist border-b-2 p-2 rounded-md flex items-center"
                        >
                            <span className="absolute crt-wich-num text-sm font-bold -top-2 left-3 bg-gray-100 rounded-xl w-7 ">
                                {wichlistCount}
                            </span>
                            <span className="hide-text-mob text-white">
                                المفضلة
                            </span>
                            <FiHeart className="text-lg mx-2 text-white" />
                        </button>

                        {localStorage.getItem("clTk") && (
                            <div className="account-btn cursor-pointer relative text-white flex items-center  border-b-2 p-2 rounded-md">
                                <MdOutlineManageAccounts className=" text-white" />
                                <span className=" text-white">حسابى</span>
                                <div className="account-list rounded-md p-3 hidden">
                                    <Link
                                        className=" bg-red-500 hover:border-slate-900 border-b-2 p-2 rounded-md"
                                        to={`/account`}
                                    >
                                        بياناتى
                                    </Link>
                                    <button
                                        onClick={logoutFunc}
                                        className="relative hover:bg-red-700 wichlist bg-red-500 border-b-2 p-2 rounded-md flex items-center"
                                    >
                                        <span className="">خروج</span>
                                        <BiLogOut className=" text-white" />
                                    </button>
                                </div>
                            </div>
                        )}
                        {!localStorage.getItem("clTk") && (
                            <div className="dropdown-categories">
                                <button className="dropbtn-categories text-white flex items-center  border-b-2 p-2 rounded-md">
                                    <MdOutlineManageAccounts className=" text-white" />
                                    <span className=" text-white">دخول</span>
                                </button>
                                <div className="dropdown-content-categories">
                                    <Link to={"/clientLogin"}>دخول</Link>
                                    <Link to={"/traderLogin"}>دخول كتاجر</Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                <div className="categories-div flex text-white justify-center gap-4 py-2 header-tow bg-white">
                    <div className="traders-container">
                        <div className="dropdown-tradres">
                            <button className="dropbtn-traders flex items-center border-b-2 p-2 rounded-md">
                                {/* <AiOutlineArrowDown /> */}
                                <span> المحلات</span>
                            </button>
                            <div className="dropdown-trader-list relative">
                                {levels &&
                                    levels.map((level) => (
                                        <div
                                            className="hover:rounded-md shops-list-container relative"
                                            key={level.id}
                                            onClick={() => whatLevel(level)}
                                        >
                                            محلات {level.name}
                                            {level.levels && (
                                                <div className="units-into-level rounded-md absolute z-50 hidden">
                                                    {level.levels &&
                                                        level.levels.map(
                                                            (lev) => (
                                                                <div
                                                                    className="rounded-md"
                                                                    onClick={() =>
                                                                        traderByContstruction(
                                                                            lev
                                                                        )
                                                                    }
                                                                    key={lev.id}
                                                                >
                                                                    {" "}
                                                                    {lev.name}
                                                                </div>
                                                            )
                                                        )}
                                                </div>
                                            )}
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
                                className="dropdown-category-list hidden relative"
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
                                                                    getSubCateg(
                                                                        sub
                                                                    )
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
                </div>
            </nav>
        </>
    );
};

export default Header;
