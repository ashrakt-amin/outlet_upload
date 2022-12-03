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

const HomePage = () => {
    return (
        <div>
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

                <Route
                    path="/traderByConstruction/:id"
                    element={<TraderByConstruction />}
                />

                <Route path="/leveltrader/:id" element={<LevelTrader />} />

                <Route path="/client/cart" element={<CartPage />} />
                <Route path="/client/wishList" element={<ClientWichList />} />
                <Route path="/account" element={<Profile />} />
                <Route path="/clientOrders" element={<ClientOrders />} />
            </Routes>
        </div>
    );
};

export default HomePage;
