import axios from "axios";
import React, { useState } from "react";
import { useEffect } from "react";
import { Link } from "react-router-dom";

import { AiFillCamera } from "react-icons/ai";
import { FcCamera } from "react-icons/fc";

const Projects = () => {
    const [projects, setProjects] = useState([]);
    const [projectName, setProjectName] = useState("");
    const [sucessMsg, setSuccessMsg] = useState("");
    const [fetchAgain, setFechAgain] = useState(false);
    const [isAddproject, setIsAddproject] = useState(false);

    const [projectType, setprojectType] = useState("");

    const [imgs, setImgs] = useState(null);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getProjects = async () => {
            try {
                const response = await axios.get(
                    `${process.env.MIX_APP_URL}/api/projects`,
                    { cancelRequest: cancelRequest.token }
                );
                setProjects(response.data.data);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getProjects();
        return () => {
            cancelRequest.cancel();
        };
    }, [fetchAgain]);

    const showConfirm = () => {
        setIsAddproject(!isAddproject);
    };

    const handleImg = (e) => {
        setImgs([...e.target.files]);
    };

    const addProject = async () => {
        setIsAddproject(!isAddproject);
        if (projectName != "") {
            const fData = new FormData();
            fData.append("name", projectName);
            fData.append("project_type", projectType);
            // imgs.map((el) => {
            //     fData.append("img[]", el);
            // });
            console.log(fData);

            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/projects`, fData)
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setProjectName("");
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 3000);
                        setFechAgain(!fetchAgain);
                        setIsAddproject(!isAddproject);
                    });
            } catch (er) {
                console.log(er);
            }
        }
    };

    const handleProjectType = (e) => {
        setprojectType(e);
        console.log(e);
    };
    return (
        <div dir="rtl" className="p-2 text-center ">
            <h1> صفحة المشاريع</h1>

            <div className="add-project-div my-4 flex justify-center items-center flex-wrap mb-5">
                {!isAddproject && (
                    <button
                        onClick={showConfirm}
                        className="bg-blue-500 rounded-md p-2 my-3"
                    >
                        إضافة مشروع
                    </button>
                )}

                {sucessMsg.length > 0 && (
                    <div className="fixed top-32 z-50 p-2 text-white text-center w-full left-0 bg-green-500">
                        {sucessMsg}
                    </div>
                )}

                {isAddproject && (
                    <button
                        onClick={addProject}
                        className="bg-cyan-500 rounded-md p-2 my-3"
                    >
                        تأكيد إضافة المشروع
                    </button>
                )}

                <input
                    value={projectName}
                    onChange={(e) => setProjectName(e.target.value)}
                    type="text"
                    className="rounded-md mx-1"
                />

                <div className="project-type">
                    <h1>اختر نوع المشروع</h1>
                    <button
                        onClick={() => handleProjectType(0)}
                        className="bg-green-400 text-white text-lg p-1 rounded-md m-1"
                    >
                        مول
                    </button>
                    <button
                        onClick={() => handleProjectType(1)}
                        className="bg-green-400 text-white text-lg p-1 rounded-md m-1"
                    >
                        شوارع
                    </button>
                </div>
            </div>
            <div className="">
                <span className="text-lg">إختر صور المشروع</span>
                <label
                    onChange={handleImg}
                    htmlFor="formId"
                    className="text-center flex justify-center"
                >
                    <FcCamera className="text-3xl cursor-pointer " />
                </label>
                <input
                    multiple
                    className="hidden"
                    name=""
                    type="file"
                    id="formId"
                />
            </div>

            <div className="projects-grid grid grid-cols-3 gap-3 rounded-md">
                {projects &&
                    projects.map((project) => (
                        <div
                            className="h-60 bg-slate-50 rounded-md shadow-md  my-2"
                            key={project.id}
                        >
                            <Link
                                className="bg-blue-200 rounded-md p-2"
                                to={`/dachboard/projects/${project.id}`}
                            >
                                {project.name}
                            </Link>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default Projects;
