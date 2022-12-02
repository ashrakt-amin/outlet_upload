import React, { useEffect, useState } from "react";

import "./traderdashboardStyle.scss";

import { Link, NavLink, Outlet, useNavigate } from "react-router-dom";
import axios from "axios";

const VendorDashboard = () => {
    const [auth, setAuth] = useState(false);

    const navig = useNavigate();

    useEffect(() => {
        let getTokenTrader = JSON.parse(localStorage.getItem("trTk"));
        if (getTokenTrader) {
            const getItems = async () => {
                try {
                    const res = await axios.get(
                        `http://127.0.0.1:8000/api/traders/trader`,
                        {
                            headers: {
                                Authorization: `Bearer ${getTokenTrader}`,
                            },
                        }
                    );
                    setAuth(true);
                    console.log(res);
                } catch (error) {
                    console.log(error);
                }
            };
            getItems();
        } else {
            navig("/traderLogin");
        }
    }, []);

    const logOutTrader = async () => {
        let getTokenTrader = JSON.parse(localStorage.getItem("trTk"));
        axios.defaults.withCredentials = true;
        await axios
            .get(`http://127.0.0.1:8000/` + "sanctum/csrf-cookie")
            .then(async (r) => {
                try {
                    let res = await axios.post(
                        "http://127.0.0.1:8000/api/logout",
                        {},
                        {
                            headers: {
                                Authorization: `Bearer ${getTokenTrader}`,
                            },
                        }
                    );
                    console.log(res);
                    localStorage.removeItem("trTk");
                    navig("/");
                } catch (er) {
                    console.log(er);
                }
            });
    };

    const [fixedNav2, setFixedNav2] = useState("");

    window.onscroll = () => {
        if (fixedNav2 == "" && window.scrollY > 40) {
            setFixedNav2("fixed-trader-nav");
        } else if (window.scrollY < 10) {
            setFixedNav2("");
        }
    };

    return (
        <>
            {/* <TraderOwnInfo /> */}

            {auth ? (
                <div className={`vendor-page-container p-2`} dir="rtl">
                    <div
                        className={`trader-pages-navigate-btns ${fixedNav2} bg-green-400 flex gap-4 rounded-md`}
                        // style={{ backgroundColor: "green" }}
                    >
                        <div
                            className={`mx-3 rounded-md p-2 my-3 flex items-start gap-3 text-white flex-wrap`}
                        >
                            <NavLink
                                className={`rounded-md p-1 border-b-2`}
                                to={`/trader/dachboard`}
                                style={({ isActive }) =>
                                    isActive
                                        ? {
                                              backgroundColor: "#e0e0e0 ",
                                          }
                                        : undefined
                                }
                            >
                                صفحتى الرئيسة
                            </NavLink>

                            {/* <NavLink
                                className={`rounded-md p-1 border-b-2`}
                                to={`/trader/dachboard/addproducts`}
                                style={({ isActive }) =>
                                    isActive
                                        ? {
                                              backgroundColor: "white",
                                          }
                                        : undefined
                                }
                            >
                                اضافة منتج
                            </NavLink> */}

                            <NavLink
                                className={`rounded-md p-1 border-b-2`}
                                to={`/trader/dachboard/myorders`}
                                style={({ isActive }) =>
                                    isActive
                                        ? {
                                              backgroundColor: "white",
                                          }
                                        : undefined
                                }
                            >
                                اوردراتى
                            </NavLink>
                            <NavLink
                                className={`rounded-md p-1 border-b-2`}
                                to={`/trader/dachboard/traderinfo`}
                                style={({ isActive }) =>
                                    isActive
                                        ? {
                                              backgroundColor: "white",
                                          }
                                        : undefined
                                }
                            >
                                معلوماتى
                            </NavLink>

                            <button
                                onClick={logOutTrader}
                                className="rounded-md p-1 border-b-2"
                            >
                                خروج
                            </button>
                        </div>
                    </div>

                    <Outlet />
                </div>
            ) : (
                <div className="flex justify-center gap-3">
                    <Link
                        className="shadow-md p-2 rounded-md"
                        to={`/traderLogin`}
                    >
                        الدخول كتاجر
                    </Link>
                    <span>هل أنت تاجر؟</span>
                </div>
            )}
        </>
    );
};

export default VendorDashboard;
