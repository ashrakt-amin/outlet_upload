import axios from "axios";
import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const LatestTowStreets = () => {
    const [streets, setStreets] = useState([]);

    const navigate = useNavigate();

    useEffect(() => {
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken == null) {
            getToken = "";
        }
        const getLatestLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/levels/latest`,
                    {
                        headers: {
                            Authorization: `Bearer ${getToken}`,
                        },
                    }
                );
                setStreets(res.data.data);
            } catch (er) {
                console.warn(er);
            }
        };
        getLatestLevels();
    }, []);

    const traderByContstruction = (constructId) => {
        navigate(`/traderByConstruction/${constructId}`);
    };

    return (
        <div dir="rtl">
            <h1 className="font-bold text-lg px-3"> شوارع مضافة حديثا</h1>
            <div dir="rtl" className="streets-constainer flex gap-5 flex-wrap">
                {!streets.length > 0 && (
                    <h1 className="p-4 bg-gray-200 text-black text-lg">
                        <marquee>Mansoura Outlet مرحبا بك فى </marquee>
                    </h1>
                )}
                {streets &&
                    streets.map((onestreet) => (
                        <div
                            className="hover:rounded-md cursor-pointer bg-gray-200 text-black p-3 m-5 rounded-md"
                            key={onestreet.id}
                            onClick={() => traderByContstruction(onestreet.id)}
                        >
                            محلات {onestreet.name}
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default LatestTowStreets;
