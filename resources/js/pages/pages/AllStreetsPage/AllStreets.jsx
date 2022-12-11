import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const AllStreets = () => {
    const [levels, setLevels] = useState([]);

    const navigate = useNavigate();

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        if (getToken == null) {
            getToken = "";
        }
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/levels`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setLevels(res.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getLevels();

        return () => {
            cancelRequest.cancel();
        };
    }, []);

    const traderByContstruction = (constructId) => {
        navigate(`/traderByConstruction/${constructId}`);
    };

    return (
        <>
            <h1 className="shadow-md p-5 rounded-md text-lg text-center">
                المحلات
            </h1>
            <div dir="rtl" className="streets-constainer flex gap-5 flex-wrap">
                {levels &&
                    levels.map((level) => (
                        <div
                            className="hover:rounded-md cursor-pointer bg-red-600 text-white p-3 m-5 rounded-md"
                            key={level.id}
                            onClick={() => traderByContstruction(level.id)}
                        >
                            محلات {level.name}
                        </div>
                    ))}
            </div>
        </>
    );
};

export default AllStreets;
