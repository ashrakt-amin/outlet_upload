import axios from "axios";
import React, { useState } from "react";
import { useEffect } from "react";
import { Link } from "react-router-dom";

const Projects = () => {
    const [projects, setProjects] = useState([]);
    const [projectName, setProjectName] = useState("");
    const [sucessMsg, setSuccessMsg] = useState("");
    const [fetchAgain, setFechAgain] = useState(false);
    const [isAddproject, setIsAddproject] = useState(false);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getProjects = async () => {
            try {
                const response = await axios.get(
                    `http://127.0.0.1:8000/api/projects`,
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

    const addProject = async () => {
        if (projectName != "") {
            try {
                axios
                    .post("http://127.0.0.1:8000/api/projects", {
                        name: projectName,
                    })
                    .then((res) => {
                        console.log(res);
                        setSuccessMsg(res.data.message);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 3000);
                        setFechAgain(!fetchAgain);
                        setIsAddproject(!isAddproject);
                    });
            } catch (er) {
                console.log(er.response.data.message);
            }
        }
    };

    return (
        <div className="p-2 text-center ">
            <h1> صفحة المشاريع</h1>

            <div className="add-project-div my-4">
                {!isAddproject && (
                    <button
                        onClick={showConfirm}
                        className="bg-cyan-500 rounded-md p-2 my-3"
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
                    onChange={(e) => setProjectName(e.target.value)}
                    type="text"
                    className="rounded-md mx-1"
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
