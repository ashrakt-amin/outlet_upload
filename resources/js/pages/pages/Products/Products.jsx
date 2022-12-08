import axios from "axios";
import React, { useEffect, useState } from "react";

import { useNavigate, useParams } from "react-router-dom";

import "./clintProductsStyle.scss";
import OneClintProduct from "../OneCliendProductComponent/OneClintProduct";

const Products = () => {
    const navigate = useNavigate("");
    const [products, setProducts] = useState([]);
    const { id } = useParams();

    // (------------------------ type select state ----------------------)
    const [typeName, setTypeName] = useState("");
    // (------------------------ type select state ----------------------)

    const [refetchData, setRefetchData] = useState(false);

    const [groupsArray, setGroupsArray] = useState([]);

    console.log(groupsArray);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));

        if (getToken == null) {
            getToken = "";
        }
        const getSubCategories = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/categories/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${getToken}`,
                        },
                    }
                );
                // setProducts(res.data.data.groups);
                // setGroupsArray(res.data.data.groups);
                console.log(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getSubCategories();

        return () => {
            cancelRequest.cancel();
        };
    }, [id, refetchData]);

    const refetch = () => setRefetchData(!refetchData);

    // (------------------------ handle type select ----------------------)

    const whatType = (type) => {
        setTypeName(type.name);
        navigate(`/products/types/${type.id}`);
    };
    // (------------------------ handle type select ----------------------)

    // (------------------------ handle type select ----------------------)

    const whatColor = (type) => {
        // console.log(type.target.value);
        if (type.target.value != "0") {
            navigate(`/products/types/${type.target.value}`);
        }
    };
    // (------------------------ handle type select ----------------------)

    return (
        <div className="clients-products-div">
            <div dir="rtl" className="select-type-div p-2">
                <div className="text-sm">إختر التصنيف</div>

                <div className="select-color-size-div">
                    <div className="types-div">
                        <select
                            className="rounded-md cursor-pointer"
                            onChange={whatColor}
                            name="itemunit"
                            id="itemunit"
                        >
                            <option value={"0"}>لم تختر بعد</option>
                            {groupsArray &&
                                groupsArray.map((onecolor) => (
                                    <option
                                        value={onecolor.id}
                                        key={onecolor.id}
                                    >
                                        {onecolor.name}
                                    </option>
                                ))}
                        </select>
                    </div>
                </div>
            </div>
            <div className="mt-5">
                {products &&
                    products.map((product) => (
                        <div key={product.id} className="">
                            {product.types &&
                                product.types.map((type) => (
                                    <div key={type.id} className="">
                                        {/* grid md:grid-cols-3 sm:grid-cols-1 gap-3 p-3 */}
                                        <div className="p-3 bg-slate-200 text-center rounded-md shadow ">
                                            {type.name}
                                        </div>
                                        <div className="gird-products flex flex-wrap justify-center pb-16 gap-4 my-4">
                                            {type.items &&
                                                type.items.map((item) => (
                                                    <div key={item.id}>
                                                        <OneClintProduct
                                                            product={item}
                                                            refetch={refetch}
                                                        />
                                                    </div>
                                                ))}
                                        </div>
                                    </div>
                                ))}
                        </div>
                    ))}
            </div>
            {/* <Outlet /> */}
        </div>
    );
};

export default Products;
// export default React.memo(Products);
