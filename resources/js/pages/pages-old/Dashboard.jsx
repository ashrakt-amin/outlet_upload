import axios from "axios";
import { useContext, useEffect, useState } from "react";
import { Route, Routes, useNavigate } from "react-router-dom";
import Header from "../components/Header";
import Sidebar from "../components/Sidebar";

import { darkTheme } from "../context/darkTheme";
import MainDach from "./AdminPages/MainDach/MainDach";

import VendorsPage from "./AdminPages/Vendors/VendorsPage";
import Customers from "./AdminPages/Customers/Customers";
import Projects from "./AdminRoutes/Projects";
import OneProject from "./AdminRoutes/OneProject";
import OneVendor from "./AdminRoutes/OneVendor";
import OneLevel from "./AdminRoutes/OneLevel";
import OneUnit from "./AdminRoutes/OneUnit";
import UsersPage from "./AdminPages/UsersPage/UsersPage";
import OneVendorProduct from "./AdminPages/Vendors/OneVendorProduct";
import AddAdvertisement from "./AdminPages/AddAdvertisment/AddAdvertisement";
import AllAddingsData from "./AdminRoutes/AllAddingsData";

function Dashboard() {
    const { isDark, isShow } = useContext(darkTheme);
    const navigate = useNavigate();

    const [isAdmin, setIsAdmin] = useState(true);

    useEffect(() => {
        let adminTrue = JSON.parse(localStorage.getItem("uTk"));
        //1 make request with token to check if user true
        console.log(adminTrue);
        const checkUserFirst = async () => {
            try {
                const res = await axios.get("${process.env.MIX_APP_URL}/", {
                    headers: { Authorization: `Bearer ${adminTrue}` },
                });
            } catch (error) {}
        };

        if (adminTrue) {
        }

        //2 if true open
        const cancelRequest = axios.CancelToken.source();
        if (!adminTrue) {
            navigate("/adminlogin");
        } else {
            setIsAdmin(true);
        }
    }, []);

    return (
        <>
            {isAdmin && (
                <div className={isDark ? "dark" : "light"}>
                    <div>
                        <Header />
                    </div>
                    <main className="w-full dark:bg-slate-800 dark:text-white flex">
                        <Sidebar isShow={isShow} />
                        <div
                            className={
                                isShow
                                    ? "w-full min-h-screen pt-24 md:pl-[15.7rem] transition-all duration-400 ease-in-out"
                                    : "w-full min-h-screen pt-24 py-10 transition-all duration-400 ease-in-out"
                            }
                        >
                            <div className="flex flex-col items-center justify-center px-6 py-3 w-full">
                                <div className="w-full ">
                                    <div className="relative shadow-md sm:rounded-lg">
                                        {/* <div className="overflow-x-auto relative shadow-md sm:rounded-lg"> */}
                                        <Routes>
                                            <Route
                                                path={`/`}
                                                element={<MainDach />}
                                            />
                                            <Route
                                                path={`/projects/*`}
                                                element={<Projects />}
                                            />
                                            <Route
                                                path={`/projects/:id`}
                                                element={<OneProject />}
                                            />

                                            <Route
                                                path={`/projects/level/:id`}
                                                element={<OneLevel />}
                                            />
                                            <Route
                                                path={`/projects/unit/:id`}
                                                element={<OneUnit />}
                                            />

                                            <Route
                                                path={`/vendors/*`}
                                                element={<VendorsPage />}
                                            />

                                            <Route
                                                path={`/vendors/:id/*`}
                                                element={<OneVendor />}
                                            />

                                            <Route
                                                path={`/vendors/:id/onevendorproduct/:id`}
                                                element={<OneVendorProduct />}
                                            />

                                            {/* <Route
                                                    path={`/vendors/:id/vendorProducts`}
                                                    element={<VendorProducts />}
                                                /> */}

                                            <Route
                                                path={`/customers`}
                                                element={<Customers />}
                                            />

                                            <Route
                                                path={`/users`}
                                                element={<UsersPage />}
                                            />

                                            <Route
                                                path={`/addAdvertisement`}
                                                element={<AddAdvertisement />}
                                            />

                                            <Route
                                                path={`/adjustAddings`}
                                                element={<AllAddingsData />}
                                            />
                                        </Routes>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            )}
        </>
    );
}
export default Dashboard;
