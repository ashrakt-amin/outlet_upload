import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";

import { Button, Menu, MenuItem } from "@mui/material";

const OneProject = () => {
    const { id } = useParams();
    const [levelName, setLevelName] = useState("");
    const [fetchAgain, setFechAgain] = useState(false);
    const [isAddLevel, setIsAddLevel] = useState(false);

    const [constructName, setConstructName] = useState("اختر عمارة");
    const [constructId, setConstructId] = useState("");

    const [oneProject, setOneProject] = useState({});

    const [levels, setLevels] = useState([]);
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getLevels = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/projects/${id}`,
                    { cancelRequest: cancelRequest.token }
                );
                setOneProject(res.data.data[0]);
                setLevels(res.data.data[0].levels);
                console.log(res.data.data);
            } catch (error) {
                console.warn(error.message);
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
            console.log("add");
            try {
                axios
                    .post("http://127.0.0.1:8000/api/levels", {
                        name: levelName,
                        construction_id: 1,
                    })
                    .then((res) => {
                        console.log(res.data);
                        setFechAgain(!fetchAgain);
                        showConfirm();
                    });
            } catch (er) {
                console.log(er.response.data.message);
            }
        }
    };

    //////////////////////// start Selection 1//////////////////////////////////
    const [anchorEl1, setAnchorEl1] = useState(null);
    const open1 = Boolean(anchorEl1);
    const handleClick1 = (event) => {
        setAnchorEl1(event.currentTarget);
    };
    const handleClose1 = (e, cnstrctId) => {
        setAnchorEl1(null);
        if (e.target.innerText !== "") {
            setConstructId(cnstrctId);
            setConstructName(e.target.innerText);
        }
    };
    //////////////////////// End Selection 1 /////////////////////////////////

    return (
        <div className="p-2 text-center">
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
                        className="bg-cyan-500 rounded-md p-2"
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

                <div className="border-2 rounded-md px-1">
                    <Button
                        id="basic-button"
                        aria-controls={open1 ? "basic-menu" : undefined}
                        aria-haspopup="true"
                        aria-expanded={open1 ? "true" : undefined}
                        onClick={handleClick1}
                    >
                        {/* <BsArrowDownCircleFill /> */}
                        <span className="mx-1">{constructName}</span>
                    </Button>
                    <Menu
                        id="basic-menu"
                        anchorEl={anchorEl1}
                        open={open1}
                        onClose={handleClose1}
                        MenuListProps={{
                            "aria-labelledby": "basic-button",
                        }}
                    >
                        {oneProject.constructions &&
                            oneProject.constructions?.map((construct) => (
                                <MenuItem
                                    key={construct.id}
                                    onClick={(e) =>
                                        handleClose1(e, construct.id)
                                    }
                                >
                                    {construct.name}
                                </MenuItem>
                            ))}
                    </Menu>
                </div>
            </div>

            <div className="levels-grid grid grid-cols-3 gap-3">
                {levels &&
                    levels.map((levl) => (
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
