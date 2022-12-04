import React, { useState } from "react";
import { useDispatch } from "react-redux";
import { useNavigate } from "react-router-dom";
import OneTypeItemProduct from "./OneTypeItemProduct";

const OneTypeItem = ({ product }) => {
    console.log(product);
    return (
        <div className="gird-products flex flex-wrap justify-center pb-16 gap-4 my-4">
            {/* {product &&
                product.map((pr) => (
                    <OneTypeItemProduct key={pr.id} oneItem={pr} />
                ))} */}
        </div>
    );
};

export default OneTypeItem;
