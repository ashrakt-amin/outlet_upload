import React from "react";
import { useState } from "react";
import { Link } from "react-router-dom";
import TraderAddingProducts from "../../TraderDashboard/TraderAddingProducts/TraderAddingProducts";
// import AddProductsToTraders from "./AddProductsToTraders";

const VendorProducts = ({ vendorProductArray }) => {
    let products = vendorProductArray.items;

    const [isAddingProduct, setIsAddingProduct] = useState(false);

    return (
        <div className="trader-products-container-dash">
            <h1 className="p-1 bg-slate-400 rounded-md text-center text-3xl">
                منتجات التاجر
            </h1>
            <div className="flex flex-wrap gap-3">
                {products &&
                    products.map((product) => (
                        <div
                            key={product.id}
                            className="one-product-div p-3 shadow-lg rounded-lg relative"
                            dir="rtl"
                            style={{ width: "250px" }}
                        >
                            <div
                                className="product-img "
                                style={{
                                    width: "250px",
                                }}
                            >
                                <img
                                    className="w-1/2 mx-auto"
                                    src={`http://127.0.0.1:8000/assets/images/uploads/items/${product.itemImages[0]?.img}`}
                                    alt="image"
                                />
                            </div>
                            <h5 className="mt-2">اسم المنتج:{product.name}</h5>

                            <div className="details font-bold cursor-pointer mt-2 border-zinc-400 border-b-2 p-2 rounded-md bg-slate-200">
                                <Link to={`onevendorproduct/${product.id}`}>
                                    تفاصيل المنتج
                                </Link>
                            </div>
                        </div>
                    ))}
            </div>

            {isAddingProduct ? (
                <button
                    onClick={() => setIsAddingProduct(!isAddingProduct)}
                    className="bg-red-600 text-white rounded-md p-2 mt-5"
                >
                    الغاء إضافة منتج
                </button>
            ) : (
                <button
                    onClick={() => setIsAddingProduct(!isAddingProduct)}
                    className="bg-blue-600 text-white rounded-md p-2 mt-5"
                >
                    إضافة منتج
                </button>
            )}
            {/* <AddProductsToTraders /> */}
            {isAddingProduct && <TraderAddingProducts />}
        </div>
    );
};

export default VendorProducts;
