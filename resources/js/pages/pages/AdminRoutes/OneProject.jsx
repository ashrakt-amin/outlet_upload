import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { FcCamera } from "react-icons/fc";

import loadicon from "./loadicon.gif";
import { useRef } from "react";

const OneProject = () => {
    const { id } = useParams();

    const [levelName, setLevelName] = useState("");

    const [successMsg, setSuccessMsg] = useState("");

    const [fetchAgain, setFechAgain] = useState(false);

    const [isAddLevel, setIsAddLevel] = useState(false);

    const [oneProject, setOneProject] = useState({});

    const [projectType, setprojectType] = useState("");

    const [projectTypeName, setprojectTypeName] = useState("");

    const [projectImgs, setprojectImgs] = useState(null);

    const [isLooding, setisLooding] = useState(true);

    const [isLooding2, setisLooding2] = useState(true);

    const [isDelete, setisDelete] = useState(false);

    const imgRef = useRef(null);
    // const [first, setfirst] = useState(second)
    const imgRef2 = useRef(null);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/projects/${id}`,
                    { cancelRequest: cancelRequest.token }
                );
                setOneProject(res.data.data);
                if (isLooding == false) {
                    setisLooding(true);
                }
                if (isLooding2 == false) {
                    setisLooding2(true);
                }
                if (isDelete == true) {
                    setisDelete(false);
                }
            } catch (error) {
                console.log(error, "project");
            }
        };
        getLevels();
        return () => {
            cancelRequest.cancel();
        };
    }, [fetchAgain]);

    const showConfirm = () => {
        setIsAddLevel(!isAddLevel);
    };

    const [imgs, setImgs] = useState(null);

    const handleImg = (e) => {
        setImgs([...e.target.files]);
    };

    console.log("log 1");

    const validateInputs = () => {
        if (levelName.length == 0) {
            setSuccessMsg(" اكتب اسم الدور او الشارع");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }
        if (imgs == null) {
            setSuccessMsg("اختر صور الدور او الشارع");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }
        if (projectType === "") {
            setSuccessMsg("اختر نوع المشروع");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }
        addLevelFunc();
    };

    const addLevelFunc = () => {
        if (levelName != "") {
            const fData = new FormData();
            setIsAddLevel(!isAddLevel);
            fData.append("name", levelName);
            fData.append("project_id", oneProject.id);
            fData.append("level_type", projectType);
            imgs.map((el) => {
                fData.append("img[]", el);
            });

            try {
                setisLooding(false);
                axios
                    .post(`${process.env.MIX_APP_URL}/api/levels`, fData)
                    .then((res) => {
                        setLevelName("");
                        imgRef.current.value = "";
                        setprojectType("");
                        setprojectTypeName("");
                        console.log(res);
                        setSuccessMsg(res.data.message);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 2000);
                        setFechAgain(!fetchAgain);
                        setIsAddLevel(!isAddLevel);
                    });
            } catch (er) {
                console.log(er);
            }
        }
    };

    console.log("log 1");

    const handleProjectType = (e, prtypename) => {
        setprojectType(e);
        setprojectTypeName(prtypename);
    };

    const deleteImg = async (projimg) => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        // console.log(primg);
        try {
            setisDelete(true);
            let res = await axios.delete(
                `${process.env.MIX_APP_URL}/api/projectImages/${projimg.id}`,
                {
                    headers: {
                        Authorization: `Bearer ${userToken}`,
                    },
                }
            );
            console.log(res);
            setSuccessMsg(res.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            setFechAgain(!fetchAgain);
        } catch (er) {
            console.log(er);
        }
    };

    console.log("project ");

    const addingimgsProject = (e) => {
        setprojectImgs([...e.target.files]);
    };

    const addProjectsImgsFunc = async () => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        if (projectImgs == null) {
            setSuccessMsg("اختر صور اولا");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
        } else {
            const fData = new FormData();

            fData.append("project_id", oneProject.id);

            projectImgs.map((el) => {
                fData.append("img[]", el);
            });

            try {
                setisLooding2(false);
                let res = await axios.post(
                    `${process.env.MIX_APP_URL}/api/projectImages`,
                    fData,
                    {
                        headers: {
                            Authorization: `Bearer ${userToken}`,
                        },
                    }
                );
                imgRef2.current.value = "";
                setSuccessMsg(res.data.message);
                setprojectImgs(null);
                setTimeout(() => {
                    setSuccessMsg("");
                }, 2000);

                setFechAgain(!fetchAgain);
            } catch (er) {
                console.log(er);
            }
        }
    };

    return (
        <div dir="rtl" className="p-2 text-center">
            <h1 className="text-lg">الشوارع او الادوار</h1>

            {successMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-green-500 p-2 text-white">
                    {successMsg}
                </div>
            )}
            <div className="add-project-div my-4 flex flex-wrap items-start">
                {!isLooding ? (
                    <div className="" style={{ width: "50px" }}>
                        <img src={loadicon} alt="" />
                    </div>
                ) : (
                    <>
                        {!isAddLevel && (
                            <button
                                onClick={showConfirm}
                                className="bg-green-500 rounded-md p-2 text-white"
                            >
                                إضافة شارع او دور
                            </button>
                        )}
                    </>
                )}

                {isAddLevel && (
                    <button
                        onClick={validateInputs}
                        className="bg-blue-500 rounded-md p-2 text-white"
                    >
                        تأكيد الاضافة
                    </button>
                )}

                <input
                    onChange={(e) => setLevelName(e.target.value)}
                    type="text"
                    className="rounded-md mx-1"
                    value={levelName}
                />

                <div className="m-2">
                    <span className="text-lg mx-2">إختر الصور</span>
                    <input
                        ref={imgRef}
                        onChange={handleImg}
                        multiple
                        className=""
                        name=""
                        type="file"
                        id="formId"
                    />
                </div>
            </div>
            <div className="project-type">
                {projectTypeName.length > 0 && (
                    <h1 className="p-1 m-1">
                        {" "}
                        نوع المشروع الذى تم اختيارة:{" "}
                        <span className="text-lg font-normal bg-slate-200 rounded-md p-1">
                            {projectTypeName}
                        </span>
                    </h1>
                )}
                <h1 className="text-xl shadow-md rounded-md p-1 m-1">
                    اختر نوع المشروع
                </h1>
                <button
                    onClick={() => handleProjectType(0, "مول")}
                    className="bg-green-400 text-white text-lg p-1 rounded-md m-1"
                >
                    مول
                </button>
                <button
                    onClick={() => handleProjectType(1, "شوارع")}
                    className="bg-green-400 text-white text-lg p-1 rounded-md m-1"
                >
                    شوارع
                </button>
            </div>

            <details>
                <summary className="cursor-pointer text-lg bg-slate-200 rounded-md mt-10">
                    إظهار صور المشروع
                </summary>
                <div className="oneproject-imgs my-4 flex flex-wrap gap-4 bg-slate-300 p-3">
                    {oneProject.images &&
                        oneProject.images.map((oneimg) => (
                            <div style={{ width: "250px" }} key={oneimg.id}>
                                <img
                                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/projects/sm/${oneimg.img}`}
                                    alt=""
                                />
                                {!isDelete ? (
                                    <button
                                        className="bg-red-500 text-white rounded-md m-2"
                                        onClick={() => deleteImg(oneimg)}
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

            <details className="cursor-pointer text-lg bg-slate-200 rounded-md m-2">
                <summary>اضافة صور للمشروع</summary>
                <div className="adding-projects-imgs">
                    <div className="m-2">
                        <input
                            onChange={addingimgsProject}
                            ref={imgRef2}
                            multiple
                            className=""
                            name=""
                            type="file"
                            id="imgsprojects"
                        />
                        <div className="add-project-imgs-btn">
                            {!isLooding2 ? (
                                <div className="" style={{ width: "50px" }}>
                                    <img src={loadicon} alt="" />
                                </div>
                            ) : (
                                <button
                                    onClick={addProjectsImgsFunc}
                                    className="bg-green-500 p-1 m-1 rounded-md text-white"
                                >
                                    اضف الان
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            </details>

            <div className="levels-grid grid grid-cols-3 gap-3 mt-10">
                {oneProject.levels &&
                    oneProject.levels.map((levl) => (
                        <div
                            key={levl.id}
                            className="p-3 bg-white rounded-md shadow-md"
                        >
                            <Link
                                className="w-full"
                                to={`/dachboard/projects/level/${levl.id}`}
                            >
                                {levl.name}
                            </Link>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default OneProject;
