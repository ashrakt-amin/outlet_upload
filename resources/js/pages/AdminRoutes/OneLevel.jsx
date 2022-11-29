import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import AddUnit from "../../Modals/AddUnit/AddUnit";

const OneLevel = () => {
    const { id } = useParams();
    const [fetchAgain, setFetchAgain] = useState(false);

    const [isAddUnit, setIsAddUnit] = useState(false);

    const [levelInfo, setLevelInfo] = useState({});

    const opnAddUnit = () => setIsAddUnit(!isAddUnit);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getUnits = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/levels/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setLevelInfo(res.data.data[0]);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getUnits();
        return () => {
            cancelRequest.cancel();
        };
    }, [fetchAgain]);

    const getUnitsFunc = () => {
        console.log("hi unit");
        setFetchAgain(!fetchAgain);
    };

    return (
        <div dir="rtl">
            {isAddUnit && (
                <AddUnit
                    fetchAgainFunc={getUnitsFunc}
                    levelInfo={levelInfo}
                    togglAddModal={opnAddUnit}
                />
            )}
            <button
                onClick={opnAddUnit}
                className="bg-cyan-500 rounded-md p-2 my-3"
            >
                إضافة وحدة
            </button>

            {/* Show units regard to this level */}

            <div className="all-units-level p-2 grid grid-cols-3 gap-3">
                {levelInfo.units &&
                    levelInfo.units.map((unit) => (
                        <div
                            key={unit.id}
                            className="single-unit bg-cyan-600 p-3 rounded-md"
                        >
                            <Link to={`/dachboard/projects/unit/${unit.id}`}>
                                اسم الوحدة : {unit.name}
                            </Link>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default OneLevel;
