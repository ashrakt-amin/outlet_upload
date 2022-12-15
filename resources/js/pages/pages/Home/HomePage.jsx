import React from "react";
import { Route, Routes } from "react-router-dom";
import CartPage from "../CartPage/CartPage";
import ClientOrders from "../ClientsPages/ClientOrders/ClientOrders";
import HeaderOne from "../HeaderOne/HeaderOne";
import HomeContent from "../HomeContent/HomeContent";
import ClientProductDetails from "../Products/ProductDetails/ClientProductDetails";
import Products from "../Products/Products";
import SingleTypeProduct from "../Products/SingleTypeProduct/SingleTypeProduct";
import Profile from "../Profile_Account/Profile";
import TraderByConstruction from "../TraderByConstruction/TraderByConstruction";

import ClientLogin from "../ClientLogin/ClientLogin";
import ClientRegister from "../ClientRegister/ClientRegister";
import ClientWichList from "../ClientsPages/ClientWishList/ClientWishList";
import LevelTrader from "../LevelTrader/LevelTrader";
import Footer from "../Footer/Footer";

import "./homePage.scss";
import AllStreets from "../AllStreetsPage/AllStreets";
import OneShop from "../OneShop/OneShop";

const HomePage = () => {
    return (
        <div className="heigh-padding-mob" style={{ marginTop: "150px" }}>
            <HeaderOne />
            <Routes>
                <Route path="/" element={<HomeContent />} />
                <Route path="/clientLogin" element={<ClientLogin />} />
                <Route path="/clientRegister" element={<ClientRegister />} />

                <Route path="/products/:id/*" element={<Products />} />

                <Route
                    path="/products/product/:id"
                    element={<ClientProductDetails />}
                />

                <Route
                    path="/products/types/:id"
                    element={<SingleTypeProduct />}
                />

                <Route path="/allStreets" element={<AllStreets />} />

                <Route
                    path="/traderByConstruction/:id"
                    element={<TraderByConstruction />}
                />

                <Route path="/oneshop/:id" element={<OneShop />} />

                <Route path="/leveltrader/:id" element={<LevelTrader />} />

                <Route path="/client/cart" element={<CartPage />} />
                <Route path="/client/wishList" element={<ClientWichList />} />
                <Route path="/account" element={<Profile />} />
                <Route path="/clientOrders" element={<ClientOrders />} />
            </Routes>
            <Footer />
        </div>
    );
};

export default HomePage;
