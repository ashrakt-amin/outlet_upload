import axios from "axios";
import React, { useEffect, useState } from "react";

const HighAdvertisement = () => {
    const [advertise, setAdvertise] = useState({});

    useEffect(() => {
        const getAdvertisements = async () => {
            try {
                axios
                    .get(`${process.env.MIX_APP_URL}api/advertisements`)
                    .then((res) => {
                        // setAdvertise(res.data.data[0]);
                        console.log(res.data);
                    });
            } catch (er) {
                console.log(er);
            }
        };
        getAdvertisements();
    }, []);

    return (
        <div className="banners-div justify-center gap-3 lg:flex lg:flex-row sm:flex-col my-5">
            {advertise?.img && (
                <a href={`${advertise?.link}`} target="_blanck">
                    <div
                        className="flex items-center"
                        style={{ maxWidth: "400px" }}
                    >
                        <img
                            src={`${process.env.MIX_APP_URL}    assets/images/uploads/advertisements/${advertise?.img}`}
                            alt=""
                        />
                    </div>
                </a>
            )}
        </div>
    );
};

export default HighAdvertisement;
