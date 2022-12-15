import axios from "axios";
import React, { useState } from "react";

const UpdateProductsImgsInDash = ({ oneImage, refetch }) => {
    const [imgVal, setImgVal] = useState(null);

    const [isUpdateImg, setIsUpdateImg] = useState(false);

    const deleteCurrentImg = async (image) => {
        let traderTk = JSON.parse(localStorage.getItem("trTk"));
        console.log(image);
        try {
            const res = await axios.delete(
                `${process.env.MIX_APP_URL}/api/itemImages/${image.id}`
                // {
                //     headers: {
                //         Authorization: `Bearer ${traderTk}`,
                //     },
                // }
            );
            console.log(res);
            refetch();
        } catch (er) {
            console.log(er);
        }
    };

    const updateCurrentImg = async (image) => {
        let traderTk = JSON.parse(localStorage.getItem("trTk"));
        console.log(imgVal);
        console.log(image);
        try {
            const res = await axios.put(
                `${process.env.MIX_APP_URL}/api/itemImages/${image.id}`,
                { img: imgVal }
                // {
                //     headers: {
                //         Authorization: `Bearer ${traderTk}`,
                //     },
                // }
            );
            console.log(res);
            refetch();
        } catch (er) {
            console.log(er);
        }
    };

    const updateImg = (e) => {
        setImgVal(e.target.files[0]);
        setIsUpdateImg(true);
    };
    return (
        <div
            className="shadow-md rounded-md h-fit p-1"
            style={{ maxWidth: "250px" }}
        >
            <img
                className="w-full"
                src={`${process.env.MIX_APP_URL}/assets/images/uploads/items/lg/${oneImage.img}`}
                alt="لا يوجد صورة"
            />

            {/* {isUpdateImg && (
                <button
                    onClick={() => updateCurrentImg(oneImage)}
                    className="bg-green-500 rounded-md p-1 my-3 text-white"
                >
                    تأكيد تعديل الصورة
                </button>
            )}
            <input type="file" onChange={updateImg} /> */}
            <button
                onClick={() => deleteCurrentImg(oneImage)}
                className="bg-red-600 rounded-md p-1 my-3 text-white"
            >
                مسح الصورة
            </button>
        </div>
    );
};

export default UpdateProductsImgsInDash;
