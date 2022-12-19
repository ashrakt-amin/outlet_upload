import React from "react";
import { Link } from "react-router-dom";

const VendorProducts = ({ vendorProductArray }) => {
    let products = vendorProductArray.items;

    console.log("log");

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
                            <Link
                                className="flex"
                                to={`onevendorproduct/${product.id}`}
                            >
                                <div
                                    className="product-img "
                                    style={{
                                        width: "100px",
                                    }}
                                >
                                    <img
                                        className="w-full h-full"
                                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/lg/${product.itemImages[0]?.img}`}
                                        alt="image"
                                    />
                                </div>
                                <h5 className="mt-2 overflow-hidden text-ellipsis w-10/12">
                                    {product.name}
                                </h5>
                            </Link>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default VendorProducts;
