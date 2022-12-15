import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { FcCamera } from "react-icons/fc";

const OneProject = () => {
    const { id } = useParams();

    const [levelName, setLevelName] = useState("");

    const [successMsg, setSuccessMsg] = useState("");

    const [fetchAgain, setFechAgain] = useState(false);

    const [isAddLevel, setIsAddLevel] = useState(false);

    const [oneProject, setOneProject] = useState({});

    const [projectType, setprojectType] = useState("");

    const [projectTypeName, setprojectTypeName] = useState("");

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/projects/${id}`,
                    { cancelRequest: cancelRequest.token }
                );
                setOneProject(res.data.data);
                console.log(res, "show project");
            } catch (error) {
                console.log(error, "project");
                console.log("error");
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

    const validateInputs = () => {
        if (levelName.length == 0) {
            setSuccessMsg("اكتب اسم المشروع");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }
        if (imgs == null) {
            setSuccessMsg("اختر صور المشروع");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }
        if (projectType == "") {
            setSuccessMsg("اختر نوع المشروع");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            return;
        }
        addLevelFunc();
    };

    const addLevelFunc = () => {
        setIsAddLevel(!isAddLevel);

        if (levelName != "") {
            const fData = new FormData();

            fData.append("name", levelName);
            fData.append("project_id", oneProject.id);
            fData.append("level_type", projectType);
            imgs.map((el) => {
                fData.append("img[]", el);
            });
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/levels`, fData)
                    .then((res) => {
                        setLevelName("");
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

    const handleProjectType = (e, prtypename) => {
        setprojectType(e);
        setprojectTypeName(prtypename);
    };

    const deleteImg = () => {
        console.log("delte");
    };

    return (
        <div dir="rtl" className="p-2 text-center">
            <h1 className="text-lg">الشوارع</h1>

            {successMsg.length > 0 && (
                <div className="fixed top-32 z-50 text-center w-full left-0 bg-red-500">
                    {successMsg}
                </div>
            )}
            <div className="add-project-div my-4 flex flex-wrap items-start">
                {!isAddLevel && (
                    <button
                        onClick={showConfirm}
                        className="bg-green-500 rounded-md p-2 text-white"
                    >
                        إضافة الشوارع
                    </button>
                )}

                {isAddLevel && (
                    <button
                        onClick={validateInputs}
                        className="bg-blue-500 rounded-md p-2"
                    >
                        تأكيد إضافة الشوارع
                    </button>
                )}

                <input
                    onChange={(e) => setLevelName(e.target.value)}
                    type="text"
                    className="rounded-md mx-1"
                    value={levelName}
                />

                <div className="m-2">
                    <span className="text-lg mx-2">إختر صور الدور</span>
                    <input
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
                <h1>اختر نوع المشروع</h1>
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
                <summary className="cursor-pointer text-lg bg-slate-200 rounded-md">
                    إظهار صور المشروع
                </summary>
                <div className="oneproject-imgs my-4">
                    {oneProject.images &&
                        oneProject.images.map((oneimg) => (
                            <div style={{ width: "250px" }} key={oneimg.id}>
                                <img
                                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/projects/sm/${oneimg.img}`}
                                    alt=""
                                />
                                <button onClick={deleteImg}>مسح الصورة</button>
                            </div>
                        ))}
                </div>
            </details>

            <div className="levels-grid grid grid-cols-3 gap-3">
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
