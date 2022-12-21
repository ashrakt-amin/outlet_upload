import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";

import LevelSlider from "../LevelsSlider/LevelSlider";

const TraderByConstruction = () => {
    const { id } = useParams();

    const [levelTraders, setLevelTraders] = useState([]);

    const [levelName, setLevelName] = useState("");

    const [oneLevel, setOneLevel] = useState({});

    const [imgs, setImgs] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        // const getSubCategories = async () => {
        //     try {
        //         const res = await axios.get(
        //             `${process.env.MIX_APP_URL}/api/levels/client/${id}`,
        //             {
        //                 cancelRequest: cancelRequest.token,
        //             }
        //         );
        //         setLevelTraders(res.data.data[0].traders);
        //         setLevelName(res.data.data[0].name);
        //     } catch (error) {
        //         console.log(error);
        //     }
        // };
        // getSubCategories();

        const getOneLevel = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/levels/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                console.log(res.data.data);
                setImgs(res.data.data.images);
                setOneLevel(res.data.data);
            } catch (error) {
                console.log(error);
            }
        };
        getOneLevel();

        return () => {
            cancelRequest.cancel();
        };
    }, [id]);

    return (
        <div dir="rtl">
            <LevelSlider imgs={imgs} />

            {!oneLevel?.units?.length > 0 && (
                <div style={{ maxWidth: "500px" }} className=" mx-auto">
                    <marquee
                        direction="right"
                        className="p-4 bg-red-600 text-white text-lg"
                    >
                        مرحبا بك فى{" Mansoura Outlet "}
                    </marquee>
                </div>
            )}

            <div className="shops-container grid grid-cols-2 gap-3   p-2">
                {oneLevel?.units &&
                    oneLevel?.units.map((oneUnit) => (
                        <Link
                            to={`/oneshop/${oneUnit.id}`}
                            key={oneUnit.id}
                            className="shadow-md rounded-md p-1"
                        >
                            <div
                                // style={{ maxWidth: "300px" }}
                                className="shope-img"
                            >
                                <img
                                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/units/sm/${oneUnit?.images[0]?.img}`}
                                    alt=""
                                />
                            </div>
                            <h1>{oneUnit.name}</h1>
                        </Link>
                    ))}
            </div>

            {/* <div className="trader-grid-div grid gap-3 lg:grid-cols-3 md:grid:cols-3 sm:grid-cols-3  place-items-center p-3">
                {levelTraders &&
                    levelTraders.map((trader, i) => (
                        <Link key={i} to={`/leveltrader/${trader.id}`}>
                            <div
                                key={trader.id}
                                style={{ width: "fit-content", height: "100%" }}
                                className="single-trader-div shadow-md p-3 text-center"
                            >
                                <div
                                    className="logo-trader-div"
                                    style={{ width: "100%" }}
                                >
                                    <img
                                        className="w-1/2 mx-auto"
                                        src={`${process.env.MIX_APP_URL}/assets/images/uploads/traders/${trader.logo}`}
                                        alt=""
                                    />
                                </div>
                                <h1>إسم التاجر:</h1>
                                <h1 className="text-nowrap">
                                    {trader.f_name} {trader.m_name}{" "}
                                    {trader.l_name}
                                </h1>
                            </div>
                        </Link>
                    ))}
            </div> */}
        </div>
    );
};

export default TraderByConstruction;
