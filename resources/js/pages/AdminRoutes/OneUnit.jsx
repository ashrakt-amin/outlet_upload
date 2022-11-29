import axios from "axios";
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

import InputLabel from "@mui/material/InputLabel";
import MenuItem from "@mui/material/MenuItem";
import FormControl from "@mui/material/FormControl";
import Select from "@mui/material/Select";

function OneUnit() {
    const { id } = useParams();

    const [traders, setTraders] = useState([]);

    const [activityArray, setActivityArray] = useState([]);
    const [activityName, setActivityName] = useState("");
    const [activityId, setActivityId] = useState("");

    const [fetchAgain, setFetchAgain] = useState(false);
    const [confirmBook, setConfrimBook] = useState(false);

    const [isActivity, setIsActivity] = useState(false);

    const [successMsg, setSuccessMsg] = useState("");

    const [traderId, setTraderId] = useState("");

    const [traderID, setTraderID] = useState("");

    const [oneUnit, setOneUnit] = useState({});

    const [selectedActivites, setSelectedActivites] = useState([]);

    const [currentActive, setCurrentActive] = useState([]);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const tokenUser = JSON.parse(localStorage.getItem("uTk"));

        const getUnits = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/units/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setOneUnit(res.data.data[0]);
                console.log(res.data.activities);
                setCurrentActive(res.data.activities);
                // setTraderID(res.data.data[0].trader.id);
            } catch (er) {
                console.log(er);
            }
        };
        getUnits();

        const getTraders = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/traders`,
                    {
                        headers: {
                            Authorization: `Bearer ${tokenUser}`,
                        },
                    }
                );
                setTraders(res.data.data);
            } catch (er) {
                console.log(er);
                console.warn(er.message);
            }
        };
        getTraders();

        const getActivities = async () => {
            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/activities`,
                    {
                        headers: {
                            Authorization: `Bearer ${tokenUser}`,
                        },
                    }
                );
                setActivityArray(res.data.data);
                console.log(res.data.data);
            } catch (er) {
                console.log(er);
                console.warn(er.message);
            }
        };
        getActivities();

        return () => {
            cancelRequest.cancel();
        };
    }, [fetchAgain]);

    const bookUnit = async () => {
        if (traderId != "") {
            try {
                axios
                    .put(`http://127.0.0.1:8000/api/units/status/${id}`, {
                        statu_id: 1, // حجز الوحدة
                        trader_id: traderId,
                    })
                    .then((res) => {
                        setSuccessMsg(res.data.message);
                        console.log(res.data);
                        setTimeout(() => {
                            setSuccessMsg("");
                        }, 3000);
                        setFetchAgain(!fetchAgain);
                    });
            } catch (er) {
                console.log(er.response.data.message);
            }
        } else {
            console.log("no trader selected");
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

    console.log(oneUnit);

    // ----------------------------- (تأكيد التعاقد) ------------------------------------\\
    const confrimBookUnit = async () => {
        try {
            axios
                .put(`http://127.0.0.1:8000/api/units/status/${id}`, {
                    statu_id: 2,
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
    const cancelContractUnit = async () => {
        try {
            axios
                .put(`http://127.0.0.1:8000/api/units/status/${id}`, {
                    statu_id: 3,
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
    // ----------------------------- (الغاء التعاقد) ------------------------------------\\

    // ----------------------------- (الغاء الحجز) ------------------------------------\\
    const cancelbookUnit = () => {
        try {
            axios
                .put(`http://127.0.0.1:8000/api/units/status/${id}`, {
                    statu_id: 0,
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

    // ----------------------------- (الغاء الحجز) ------------------------------------\\

    const handleActivity = (oneActivity) => {
        setActivityName(oneActivity.name);
        setActivityId(oneActivity.id);
        console.log(oneActivity);
        setSelectedActivites([...selectedActivites, oneActivity]);
    };

    const sendActivities = async () => {
        console.log(oneUnit.id);

        if (activityId == "") {
            console.log("id empty");
            setSuccessMsg("اختر");
            return;
        } else {
            console.log("id");
            try {
                axios
                    .post(`http://127.0.0.1:8000/api/units/activities`, {
                        unit_id: oneUnit.id,
                        trader_id: oneUnit.trader.id,
                        activity_id: selectedActivites,
                    })
                    .then((res) => {
                        console.log(res);
                        // setSuccessMsg(res.data.message);
                        // setTimeout(() => {
                        //     setSuccessMsg("");
                        // }, 3000);
                        // setFetchAgain(!fetchAgain);
                    });
            } catch (er) {
                console.log(er);
            }
        }
    };

    console.log(traderId);

    return (
        <div className="p-2" dir="rtl">
            <div className="unit-operations">
                {successMsg.length > 0 && (
                    <div className="fixed top-32 z-50 text-center w-full left-0 bg-red-500">
                        {successMsg}
                    </div>
                )}
                {oneUnit.statu != undefined && (
                    <>
                        {oneUnit.statu.id == 0 && (
                            <div className="book-btns">
                                {confirmBook ? (
                                    <>
                                        <button
                                            onClick={() => bookUnit(1)}
                                            className="bg-green-500 text-white rounded-md p-2 my-3"
                                        >
                                            تأكيد الحجز
                                        </button>
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
                                                                handleCategg(
                                                                    trader
                                                                )
                                                            }
                                                            value={
                                                                trader.f_name
                                                            }
                                                        >
                                                            {trader.f_name}
                                                        </MenuItem>
                                                    ))}
                                            </Select>
                                        </FormControl>
                                    </>
                                ) : (
                                    <button
                                        onClick={showBookBtn}
                                        className="bg-green-500 text-white rounded-md p-2 my-3"
                                    >
                                        حجز
                                    </button>
                                )}
                            </div>
                        )}
                    </>
                )}

                {oneUnit.statu != undefined && (
                    <>
                        {oneUnit.statu.id == 1 && (
                            <>
                                <button
                                    onClick={confrimBookUnit}
                                    className="bg-green-500 text-white rounded-md p-2 my-3 mx-3"
                                >
                                    إتمام التعاقد
                                </button>
                                <button
                                    onClick={cancelbookUnit}
                                    className="bg-red-500 text-white rounded-md p-2 my-3"
                                >
                                    إلغاء الحجز
                                </button>
                            </>
                        )}

                        {oneUnit.statu.id == 2 && (
                            <button
                                onClick={cancelContractUnit}
                                className="bg-red-500 text-white rounded-md p-2 my-3"
                            >
                                إلغاء التعاقد
                            </button>
                        )}

                        {oneUnit.statu.id == 3 && (
                            <div className="book-btns">
                                {confirmBook ? (
                                    <>
                                        <button
                                            onClick={() => bookUnit(1)}
                                            className="bg-green-500 text-white rounded-md p-2 my-3"
                                        >
                                            تأكيد الحجز
                                        </button>
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
                                                                handleCategg(
                                                                    trader
                                                                )
                                                            }
                                                            value={
                                                                trader.f_name
                                                            }
                                                        >
                                                            {trader.f_name}
                                                        </MenuItem>
                                                    ))}
                                            </Select>
                                        </FormControl>
                                    </>
                                ) : (
                                    <button
                                        onClick={showBookBtn}
                                        className="bg-green-500 text-white rounded-md p-2 my-3"
                                    >
                                        حجز
                                    </button>
                                )}
                            </div>
                        )}
                    </>
                )}
            </div>

            <h1 className="text-center bg-teal-500 p-2 my-3 text-yellow-50">
                معلومات الوحدة
            </h1>

            <div className="deposit my-3 bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                حالة الوحدة : {oneUnit.statu != undefined && oneUnit.statu.name}
            </div>
            <div className="one-unit-info-div grid grid-cols-3 gap-4 mb-4">
                <div className="unitname bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    إسم الوحدة : {oneUnit.name}
                </div>
                <div className="deposit bg-blue-700 p-2 rounded-md text-center text-yellow-50">
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
                </div>
                <div className="unit-price bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    اسم التاجر :{" "}
                    {oneUnit?.trader != null &&
                        `${oneUnit.trader.f_name} ${oneUnit.trader.m_name} ${oneUnit.trader.l_name}`}
                </div>
                <div className="unit-price bg-blue-700 p-2 rounded-md text-center text-yellow-50">
                    هاتف التاجر :{" "}
                    {oneUnit?.trader != null && `${oneUnit.trader.phone}`}
                </div>
            </div>
            <h1>نشاطات الوحدة</h1>
            <div className="activites-unit flex gap-4">
                {currentActive &&
                    currentActive.map((active) => (
                        <div
                            className="shadow-md p-1 rounded-md"
                            key={active.id}
                        >
                            {active.name}
                        </div>
                    ))}
            </div>

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

            <div className="selected-activites-show">
                <h1>النشاطات المختارة</h1>
                {selectedActivites.length > 0
                    ? selectedActivites.map((active) => (
                          <div
                              className="shadow-md p-2 m-2 rounded-md"
                              key={active.id}
                          >
                              {active.name}
                          </div>
                      ))
                    : "لم يتم الاختيار بعد"}
            </div>
        </div>
    );
}

export default OneUnit;
