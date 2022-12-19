import axios from "axios";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

import InputLabel from "@mui/material/InputLabel";
import MenuItem from "@mui/material/MenuItem";
import FormControl from "@mui/material/FormControl";
import Select from "@mui/material/Select";

function OneUnit() {
    const { id } = useParams();

    // const [traders, setTraders] = useState([]);

    // const [activityArray, setActivityArray] = useState([]);
    // const [activityName, setActivityName] = useState("");
    // const [activityId, setActivityId] = useState("");

    const [fetchAgain, setFetchAgain] = useState(false);
    const [confirmBook, setConfrimBook] = useState(false);

    // const [isActivity, setIsActivity] = useState(false);

    const [successMsg, setSuccessMsg] = useState("");

    const [selectTraderMstg, setSelectTraderMstg] = useState("");

    const [traderId, setTraderId] = useState("");

    const [oneUnit, setOneUnit] = useState({});

    const [unitImgs, setunitImgs] = useState(null);

    const [isDelete, setisDelete] = useState(false);

    const [isAddImgs, setisAddImgs] = useState(false);

    // const [selectedActivites, setSelectedActivites] = useState([]);

    // const [currentActive, setCurrentActive] = useState([]);

    // const [nextStatus, setNextStatus] = useState({});

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const tokenUser = JSON.parse(localStorage.getItem("uTk"));

        const getUnits = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/units/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setOneUnit(res.data.data);
                if (isDelete == true) {
                    setisDelete(false);
                }
                // console.log(res);
                // setNextStatus(res.data.next_Statu);
            } catch (er) {
                console.log(er);
            }
        };
        getUnits();

        // const getTraders = async () => {
        //     try {
        //         const res = await axios.get(
        //             `${process.env.MIX_APP_URL}/api/traders`
        //         );
        //         setTraders(res.data.data);
        //     } catch (er) {
        //         console.log(er);
        //         console.warn(er.message);
        //     }
        // };
        // getTraders();

        // const getActivities = async () => {
        //     try {
        //         const res = await axios.get(
        //             `${process.env.MIX_APP_URL}/api/activities`,
        //             {
        //                 headers: {
        //                     Authorization: `Bearer ${tokenUser}`,
        //                 },
        //             }
        //         );
        //         setActivityArray(res.data.data);
        //         console.log(res.data);
        //     } catch (er) {
        //         console.log(er);
        //     }
        // };
        // getActivities();

        return () => {
            cancelRequest.cancel();
        };
    }, [fetchAgain]);

    const bookUnit = async (statusId) => {
        if (traderId != "") {
            try {
                axios
                    .put(`${process.env.MIX_APP_URL}/api/units/status/${id}`, {
                        statu_id: statusId, // حجز الوحدة
                        trader_id: traderId,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 3000);
                        setFetchAgain(!fetchAgain);
                    });
            } catch (er) {
                console.log(er);
            }
        } else {
            setSelectTraderMstg("اختر التاجر اولا");
            setTimeout(() => {
                setSelectTraderMstg("");
            }, 3000);
        }
    };
    const showBookBtn = () => {
        setConfrimBook(!confirmBook);
    };
    const [traderName, setTraderName] = useState("");
    const categoryHandle = (e) => {
        setTraderName(e.target.value);
    };

    const handleCategg = (tr) => {
        setTraderId(tr.id);
    };

    // ----------------------------- (تأكيد التعاقد) ------------------------------------\\
    const confirmbookUnit = async (nexst) => {
        try {
            axios
                .put(`${process.env.MIX_APP_URL}/api/units/status/${id}`, {
                    statu_id: nexst,
                    trader_id: oneUnit.trader.id,
                })
                .then((res) => {
                    console.log(res);
                    setSuccessMsg(res.data.message);
                    setTimeout(() => {
                        setSuccessMsg("");
                    }, 3000);
                    setFetchAgain(!fetchAgain);
                });
        } catch (er) {
            console.log(er.response.data.message);
        }
    };
    // ----------------------------- (تأكيد التعاقد) ------------------------------------\\

    // ----------------------------- (الغاء التعاقد) ------------------------------------\\
    // const cancelContractUnit = async () => {
    //     console.log(id);
    //     console.log(oneUnit);
    //     try {
    //         axios
    //             .put(`${process.env.MIX_APP_URL}/api/units/status/${id}`, {
    //                 statu_id: 3,
    //                 trader_id: oneUnit.trader.id,
    //             })
    //             .then((res) => {
    //                 console.log(res);
    //                 setSuccessMsg(res.data.message);
    //                 setTimeout(() => {
    //                     setSuccessMsg("");
    //                 }, 3000);
    //                 setFetchAgain(!fetchAgain);
    //             });
    //     } catch (er) {
    //         // console.log(er.response.data.message);
    //         console.log(er);
    //     }
    // };
    // ----------------------------- (الغاء التعاقد) ------------------------------------\\

    // ----------------------------- (الغاء الحجز) ------------------------------------\\
    const cancelbookUnit = () => {
        try {
            axios
                .put(`${process.env.MIX_APP_URL}/api/units/status/${id}`, {
                    statu_id: 0,
                    trader_id: oneUnit.trader.id,
                })
                .then((res) => {
                    setConfrimBook(false);
                    setSuccessMsg(res.data.message);
                    setTimeout(() => {
                        setSuccessMsg("");
                    }, 3000);
                    setFetchAgain(!fetchAgain);
                });
        } catch (er) {
            console.log(er);
        }
    };

    // ----------------------------- (الغاء الحجز) ------------------------------------\\

    // const handleActivity = (oneActivity) => {
    //     setActivityName(oneActivity.name);
    //     setActivityId(oneActivity.id);

    //     const currentActivityArray = selectedActivites;

    //     if (currentActivityArray.length == 0) {
    //         setSelectedActivites([...selectedActivites, oneActivity]);
    //     } else {
    //         let trueOrFalse = selectedActivites.some((activity) => {
    //             if (activity.id == oneActivity.id) {
    //                 return true;
    //             } else {
    //                 return false;
    //             }
    //         });
    //         if (trueOrFalse == false) {
    //             setSelectedActivites([...selectedActivites, oneActivity]);
    //         }
    //     }
    // };

    // const sendActivities = async () => {
    //     if (activityId == "") {
    //         setSuccessMsg("اختر");
    //         return;
    //     } else {
    //         try {
    //             axios
    //                 .post(`${process.env.MIX_APP_URL}/api/units/activities`, {
    //                     unit_id: oneUnit.id,
    //                     trader_id: oneUnit.trader.id,
    //                     activity_id: selectedActivites,
    //                 })
    //                 .then((res) => {
    //                     setIsActivity(!isActivity);
    //                     setSuccessMsg(res.data.message);
    //                     setTimeout(() => {
    //                         setSuccessMsg("");
    //                     }, 3000);
    //                     setFetchAgain(!fetchAgain);
    //                 });
    //         } catch (er) {
    //             console.log(er);
    //         }
    //     }
    // };

    // const deleteCurrentAcitity = (deletCurrentActiv) => {
    //     let currentActivityArray = selectedActivites;
    //     let newCrruntActivityArray = currentActivityArray.filter(
    //         (oneactive) => {
    //             return oneactive.id !== deletCurrentActiv.id;
    //         }
    //     );
    //     setSelectedActivites(newCrruntActivityArray);
    // };

    const deleteUnitImg = async (oneimg) => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        try {
            setisDelete(true);
            let res = await axios.delete(
                `${process.env.MIX_APP_URL}/api/unitImages/${oneimg.id}`,
                {
                    headers: {
                        Authorization: `Bearer ${userToken}`,
                    },
                }
            );
            // console.log(res);
            setSuccessMsg(res.data.message);
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
            setFetchAgain(!fetchAgain);
        } catch (er) {
            console.log(er);
        }
    };

    const handleUnitImgs = (e) => {
        setunitImgs([...e.target.files]);
    };

    const addUnitImgsFunc = async () => {
        const userToken = JSON.parse(localStorage.getItem("uTk"));
        if (unitImgs == null) {
            setSuccessMsg("اختر صور اولا");
            setTimeout(() => {
                setSuccessMsg("");
            }, 2000);
        } else {
            const fData = new FormData();

            fData.append("unit_id", oneUnit.id);

            unitImgs.map((el) => {
                fData.append("img[]", el);
            });

            try {
                let res = await axios.post(
                    `${process.env.MIX_APP_URL}/api/unitImages`,
                    fData,
                    {
                        headers: {
                            Authorization: `Bearer ${userToken}`,
                        },
                    }
                );
                setSuccessMsg(res.data.message);
                setunitImgs(null);
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
        <div className="p-2" dir="rtl">
            <div className="unit-operations">
                {successMsg.length > 0 && (
                    <div className="fixed top-32 z-50 text-center w-full left-0 bg-green-500 p-1 text-white">
                        {successMsg}
                    </div>
                )}
                {selectTraderMstg.length > 0 && (
                    <div className="fixed top-10 z-50 text-center w-full p-1 text-lg left-0 bg-red-500">
                        {selectTraderMstg}
                    </div>
                )}
                {/* 
                <div className="book-btns">
                    {confirmBook ? (
                        <>
                            {nextStatus == false && (
                                <button
                                    onClick={() => bookUnit(nextStatus.id)}
                                    className="bg-green-500 text-white rounded-md p-2 my-3"
                                >
                                    تأكيد {nextStatus.name}
                                </button>
                            )}
                            {nextStatus == false && (
                                <FormControl
                                    sx={{ m: 1, minWidth: 120 }}
                                    size="small"
                                >
                                    <InputLabel id="demo-select-small">
                                        التجار
                                    </InputLabel>
                                    <Select
                                        labelId="demo-select-small"
                                        id="demo-select-small"
                                        value={traderName}
                                        label="التجار"
                                        onChange={categoryHandle}
                                    >
                                        {traders &&
                                            traders.map((trader) => (
                                                <MenuItem
                                                    key={trader.id}
                                                    onClick={() =>
                                                        handleCategg(trader)
                                                    }
                                                    value={trader.f_name}
                                                >
                                                    {trader.f_name}
                                                </MenuItem>
                                            ))}
                                    </Select>
                                </FormControl>
                            )}
                        </>
                    ) : (
                        <>
                            {nextStatus.id == 1 && (
                                <button
                                    onClick={showBookBtn}
                                    className="bg-green-500 text-white rounded-md p-2 my-3"
                                >
                                    حجز
                                </button>
                            )}
                        </>
                    )}
                </div>

                {nextStatus.id == 2 && (
                    <button
                        onClick={() => confirmbookUnit(nextStatus.id)}
                        className="bg-green-500 text-white rounded-md p-2 my-3"
                    >
                        تأكيد {nextStatus.name}
                    </button>
                )}

                {nextStatus.id == 2 && (
                    <button
                        onClick={cancelbookUnit}
                        className="bg-red-500 text-white rounded-md p-2 mx-2"
                    >
                        الغاء الحجز
                    </button>
                )}

                {!confirmBook && (
                    <>
                        {nextStatus == false && (
                            <button
                                onClick={showBookBtn}
                                className="bg-green-500 text-white rounded-md p-2 my-3"
                            >
                                حجز
                            </button>
                        )}
                    </>
                )} */}
            </div>

            <h1 className="text-center bg-white shadow-lg p-2 my-3 text-black">
                معلومات المحل
            </h1>

            {/*إظهار صور المحل */}
            <details className="unit-imgs">
                <summary className="cursor-pointer text-lg bg-slate-200 rounded-md">
                    اظهار صور المحل
                </summary>
                <div className="my-4 flex flex-wrap gap-4 bg-slate-300 p-3">
                    {oneUnit.images &&
                        oneUnit.images.map((oneimg) => (
                            <div key={oneimg.id} style={{ width: "150px" }}>
                                <img
                                    src={`${process.env.MIX_APP_URL}/assets/images/uploads/units/sm/${oneimg.img}`}
                                    alt=""
                                />
                                {!isDelete ? (
                                    <button
                                        onClick={() => deleteUnitImg(oneimg)}
                                        className="bg-red-500 p-1 m-1 rounded-md"
                                    >
                                        {" "}
                                        مسح الصور
                                    </button>
                                ) : (
                                    "يتم المسح"
                                )}
                            </div>
                        ))}
                </div>
            </details>

            {/* اضافة صور المحل */}
            <details className="cursor-pointer text-lg bg-slate-200 rounded-md m-2">
                <summary>اضافة صور للمحل</summary>
                <div className="adding-projects-imgs">
                    <div className="m-2">
                        <input
                            onChange={handleUnitImgs}
                            multiple
                            className=""
                            name=""
                            type="file"
                            id="imgsprojects"
                        />
                        <div className="add-project-imgs-btn">
                            <button
                                onClick={addUnitImgsFunc}
                                className="bg-green-500 p-1 m-1 rounded-md"
                            >
                                اضف الان
                            </button>
                        </div>
                    </div>
                </div>
            </details>
            {/* <div className="deposit my-3 bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                حالة الوحدة : {oneUnit.statu != undefined && oneUnit.statu.name}
            </div> */}
            {/* <h1>نشاطات الوحدة</h1> */}
            {/* <div className="activites-unit flex gap-4 mb-5">
                {oneUnit.activities &&
                    oneUnit.activities.map((active) => (
                        <div
                            className="shadow-md p-1 rounded-md"
                            key={active.id}
                        >
                            {active.name}
                        </div>
                    ))}
            </div> */}
            <div className="unitname bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                إسم المحل : {oneUnit.name}
            </div>
            <div className="one-unit-info-div mb-4">
                {/* <div className="deposit bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    عدد أشهر التأمين : {oneUnit.deposit} أشهر
                </div>
                <div className="deposit bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    عدد أشهر الإيجار : {oneUnit.rents_count} شهر
                </div>
                <div className="meter-price bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    سعر المتر : {oneUnit.price_m} جنية
                </div>
                <div className="unit-size bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    مساحة الوحدة : {oneUnit.space} متر
                </div>
                <div className="unit-price bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    سعر الوحدة :{" "}
                    {oneUnit.unit_value != null && oneUnit.unit_value} جنية
                </div> */}

                {oneUnit?.trader != null && (
                    <div className="flex flex-wrap items-start gap-4">
                        <div className="unit-price m-3 bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                            اسم التاجر :{" "}
                            {oneUnit?.trader != null &&
                                `${oneUnit.trader.f_name} ${oneUnit.trader.m_name} ${oneUnit.trader.l_name}`}
                        </div>
                        <div className="unit-price m-3 bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                            هاتف التاجر الاول:{" "}
                            {oneUnit?.trader != null &&
                                `${oneUnit.trader.phone}`}
                        </div>
                        <div className="unit-price m-3 bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                            هاتف التاجر الثانى: {oneUnit.trader.phone2}
                        </div>
                        <div
                            className="trader-img-logo m-3"
                            style={{ width: "200px", height: "200px" }}
                        >
                            <h1>صورة التاجر</h1>
                            <img
                                className="w-full h-full"
                                src={`${process.env.MIX_APP_URL}/app/assets/images/uploads/traders/${oneUnit?.trader?.logo}`}
                                alt=""
                            />
                        </div>
                    </div>
                )}
            </div>

            {/* {oneUnit?.statu?.id == 2 && (
                <>
                    <FormControl
                        className="form-select"
                        sx={{ m: 1, minWidth: 160 }}
                        size="small"
                    >
                        <InputLabel id="demo-select-small"> النشاط</InputLabel>
                        <Select
                            labelId="demo-select-small"
                            id="demo-select-small"
                            value={activityName}
                            label="الدور"
                        >
                            {activityArray &&
                                activityArray.map((active) => (
                                    <MenuItem
                                        key={active.id}
                                        onClick={() => handleActivity(active)}
                                        value={active.name}
                                    >
                                        {active.name}
                                    </MenuItem>
                                ))}
                        </Select>
                    </FormControl>
                    <h1>النشاطات المختارة</h1>
                    <div className="selected-activites-show flex gap-5 flex-wrap">
                        {selectedActivites.length > 0
                            ? selectedActivites.map((active) => (
                                  <div
                                      className="shadow-md p-2 m-2 rounded-md"
                                      key={active.id}
                                  >
                                      {active.name}
                                      <button
                                          onClick={() =>
                                              deleteCurrentAcitity(active)
                                          }
                                          className="bg-red-400 rounded-sm text-xs p-1"
                                      >
                                          مسح
                                      </button>
                                  </div>
                              ))
                            : "لم يتم الاختيار بعد"}
                    </div>
                    {!isActivity ? (
                        <button
                            onClick={() => setIsActivity(true)}
                            className="bg-green-400 text-white rounded-md p-2 my-3 mx-3"
                        >
                            اضافة النشاط
                        </button>
                    ) : (
                        <button
                            onClick={sendActivities}
                            className="bg-green-500 text-white rounded-md p-2 my-3 mx-3"
                        >
                            تأكيد اضافة النشاط
                        </button>
                    )}
                </>
            )} */}
        </div>
    );
}

export default OneUnit;
