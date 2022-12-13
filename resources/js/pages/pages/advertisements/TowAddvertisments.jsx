import axios from "axios";
import React, { useEffect, useState } from "react";
const TowAddvertisments = () => {
    const [advertise, setAdvertise] = useState([]);

    useEffect(() => {
        const getAdvertisements = async () => {
            try {
                axios
                    .get(`${process.env.MIX_APP_URL}/api/advertisements`)
                    .then((res) => {
                        setAdvertise(res.data.data);
                    });
            } catch (er) {
                console.log(er);
            }
        };
        getAdvertisements();
    }, []);

    return (
        <div className="my-5">
            {/* <h1 className="p-2 bg-slate-500 text-white text-center my-5">
                اعلانات
            </h1> */}
            {advertise.length > 0 && (
                <div className="tow-advertisements-contaienr flex justify-center gap-5">
                    <a href={`${advertise?.link}`} target="_blanck">
                        <div
                            className="flex items-center"
                            style={{ maxWidth: "400px" }}
                        >
                            <img
                                src={`${process.env.MIX_APP_URL}/assets/images/uploads/advertisements/${advertise[1]?.img}`}
                                alt=""
                            />
                        </div>
                    </a>

                    <a href={`${advertise?.link}`} target="_blanck">
                        <div
                            className="flex items-center"
                            style={{ maxWidth: "400px" }}
                        >
                            <img
                                src={`${process.env.MIX_APP_URL}/assets/images/uploads/advertisements/${advertise[2]?.img}`}
                                alt=""
                            />
                        </div>
                    </a>
                </div>
            )}
        </div>
    );
};

export default TowAddvertisments;
