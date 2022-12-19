import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import AddUnit from "../../Modals/AddUnit/AddUnit";

import loadicon from "./loadicon.gif";
import { useRef } from "react";

const OneLevel = () => {
    const { id } = useParams();
    const [fetchAgain, setFetchAgain] = useState(false);

    const [isAddUnit, setIsAddUnit] = useState(false);

    const [levelInfo, setLevelInfo] = useState({});

    const [successMsg, setSuccessMsg] = useState("");

    const [levelImgs, setLevelImgs] = useState(null);

    const [isLooding, setisLooding] = useState(true);

    const [isDelete, setisDelete] = useState(false);

    const imgRef = useRef(null);

    const opnAddUnit = () => setIsAddUnit(!isAddUnit);
    console.log(levelInfo);
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getUnits = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/levels/${id}`
                );
                setLevelInfo(res.data.data);
                if (isLooding == false) {
                    setisLooding(true);
                }
                if (isDelete == true) {
                    setisDelete(false);
                }
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
        setFetchAgain(!fetchAgain);
    };

    const deleteLevelImg = async (levelimg) => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        try {
            setisDelete(true);
            let res = await axios.delete(
                `${process.env.MIX_APP_URL}/api/levelImages/${levelimg.id}`,
                {
                    headers: {
                        Authorization: `Bearer ${userToken}`,
                    },
                }
            );
            setSuccessMsg(res.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            setFetchAgain(!fetchAgain);
        } catch (er) {
            console.log(er);
        }
    };

    const addLevelImgs = (e) => {
        setLevelImgs([...e.target.files]);
    };

    const addLevelImgsFunc = async () => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        if (levelImgs == null) {
            setSuccessMsg("اختر صور اولا");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
        } else {
            const fData = new FormData();

            fData.append("level_id", levelInfo.id);

            levelImgs.map((el) => {
                fData.append("img[]", el);
            });

            try {
                setisLooding(false);
                let res = await axios.post(
                    `${process.env.MIX_APP_URL}/api/levelImages`,
                    fData,
                    {
                        headers: {
                            Authorization: `Bearer ${userToken}`,
                        },
                    }
                );
                setSuccessMsg(res.data.message);
                setLevelImgs(null);
                imgRef.current.value = "";
                setTimeout(() => {
                    setSuccessMsg("");
                }, 2000);
                setFetchAgain(!fetchAgain);
            } catch (er) {
                console.log(er);
            }
        }
    };

    return (
        <div dir="rtl" className="p-1">
            <h1 className="bg-slate-300 text-center text-xl">
                الدور او الشارع
            </h1>
            {successMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-green-500 p-2 text-white">
                    {successMsg}
                </div>
            )}
            {isAddUnit && (
                <AddUnit
                    fetchAgainFunc={getUnitsFunc}
                    levelInfo={levelInfo}
                    togglAddModal={opnAddUnit}
                />
            )}
            <button
                onClick={opnAddUnit}
                className="bg-blue-500 rounded-md p-2 my-3 font-bold text-lg text-white"
            >
                إضافة محل
            </button>

            {/* صور الدور */}

            <details>
                <summary className="cursor-pointer text-lg bg-slate-200 rounded-md mt-10">
                    اظهار صور الدور او الشارع
                </summary>
                <div className="my-4 flex flex-wrap gap-4 bg-slate-300 p-3">
                    {levelInfo.images &&
                        levelInfo.images.map((img) => (
                            <div key={img.id} style={{ width: "150px" }}>
                                <img
                                    src={`${process.env.MIX_APP_URL}/assets/levels/sm/${img.img}`}
                                    alt=""
                                />
                                {!isDelete ? (
                                    <button
                                        onClick={() => deleteLevelImg(img)}
                                        className="bg-red-500 p-1 m-1 rounded-md"
                                    >
                                        مسح الصورة
                                    </button>
                                ) : (
                                    "يتم المسح"
                                )}
                            </div>
                        ))}
                </div>
            </details>

            {/* اضافة صور للدور */}
            <details className="cursor-pointer text-lg bg-slate-200 rounded-md m-2">
                <summary>اضافة صور للدور او الشارع</summary>
                <div className="adding-projects-imgs">
                    <div className="m-2">
                        <input
                            ref={imgRef}
                            onChange={addLevelImgs}
                            multiple
                            className=""
                            name=""
                            type="file"
                            id="imgsprojects"
                        />
                        {!isLooding ? (
                            <div className="" style={{ width: "50px" }}>
                                <img src={loadicon} alt="" />
                            </div>
                        ) : (
                            <div className="add-project-imgs-btn">
                                <button
                                    onClick={addLevelImgsFunc}
                                    className="bg-green-500 p-1 m-1 rounded-md text-white"
                                >
                                    اضف الان
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            </details>

            <div className="all-units-level p-2 grid grid-cols-3 gap-3">
                {levelInfo?.units &&
                    levelInfo.units.map((unit) => (
                        <div
                            key={unit.id}
                            className="single-unit bg-blue-600 text-white p-3 rounded-md"
                        >
                            <Link to={`/dachboard/projects/unit/${unit.id}`}>
                                اسم المحل : {unit.name}
                            </Link>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default OneLevel;
