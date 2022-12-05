import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";


const OneProject = () => {
    const { id } = useParams();

    const [levelName, setLevelName] = useState("");

    const [fetchAgain, setFechAgain] = useState(false);

    const [isAddLevel, setIsAddLevel] = useState(false);

    const [oneProject, setOneProject] = useState({});
    // const [levels, setLevels] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/projects/${id}`,
                    { cancelRequest: cancelRequest.token }
                );
                setOneProject(res.data.data);
                console.log(res,'projoect 28');
            } catch (error) {
                console.log(error,'project');
                console.log('error');
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

    const addLevelFunc = () => {
        if (levelName != "") {
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/levels`, {
                        name: levelName,
                        project_id: oneProject.id,
                    })
                    .then((res) => {
                        console.log(res.data);
                        setLevelName("");
                        setFechAgain(!fetchAgain);
                        setIsAddLevel(!isAddLevel);
                    });
            } catch (er) {
                console.log(er);
            }
        }
    };

    return (
        <div dir="rtl" className="p-2 text-center">
            <h1>الطوابق</h1>

            <div className="add-project-div my-4 flex items-start">
                {!isAddLevel && (
                    <button
                        onClick={showConfirm}
                        className="bg-cyan-500 rounded-md p-2"
                    >
                        إضافة طابق
                    </button>
                )}

                {isAddLevel && (
                    <button
                        onClick={addLevelFunc}
                        className="bg-blue-500 rounded-md p-2"
                    >
                        تأكيد إضافة طابق
                    </button>
                )}

                <input
                    onChange={(e) => setLevelName(e.target.value)}
                    type="text"
                    className="rounded-md mx-1"
                    value={levelName}
                />
            </div>

            <div className="levels-grid grid grid-cols-3 gap-3">
                {oneProject.levels &&
                    oneProject.levels.map((levl) => (
                        <div key={levl.id} className="p-3 bg-slate-500">
                            <Link to={`/dachboard/projects/level/${levl.id}`}>
                                {levl.name}
                            </Link>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default OneProject;
