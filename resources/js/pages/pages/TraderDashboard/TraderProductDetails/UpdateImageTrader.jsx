import React from "react";

const UpdateImageTrader = ({ oneImage }) => {
    return (
        <div
            className="shadow-md rounded-md h-fit p-1"
            style={{ maxWidth: "250px" }}
        >
            <img
                className="w-full"
                src={`http://127.0.0.1:8000/assets/images/uploads/items/${oneImage.img}`}
                alt="لا يوجد صورة"
            />
        </div>
    );
};

export default UpdateImageTrader;
