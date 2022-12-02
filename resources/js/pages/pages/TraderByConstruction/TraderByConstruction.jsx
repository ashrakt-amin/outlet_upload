import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";

const TraderByConstruction = () => {
    const { id } = useParams();

    const [levelTraders, setLevelTraders] = useState([]);

    const [levelName, setLevelName] = useState("");

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        let getToken = JSON.parse(localStorage.getItem("clTk"));
        const getSubCategories = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/levels/client/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setLevelTraders(res.data.data[0].traders);
                setLevelName(res.data.data[0].name);
            } catch (error) {
                console.log(error);
            }
        };
        getSubCategories();

        return () => {
            cancelRequest.cancel();
        };
    }, [id]);

    return (
        <div dir="rtl">
            <h1
                className="text-center p-3 shadow-md m-3 text-white rounded-md"
                style={{ backgroundColor: "rgb(220, 26, 33" }}
            >
                {" "}
                محلات {levelName}
            </h1>

            <div className="trader-grid-div grid gap-3 lg:grid-cols-3 md:grid:cols-2  place-items-center p-3">
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
                                        src={`http://127.0.0.1:8000/assets/images/uploads/traders/${trader.logo}`}
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
            </div>
        </div>
    );
};

export default TraderByConstruction;
