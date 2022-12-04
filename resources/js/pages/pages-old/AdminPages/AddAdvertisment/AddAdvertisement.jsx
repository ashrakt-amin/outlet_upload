import { FormControl, InputLabel, MenuItem, Select } from "@mui/material";
import axios from "axios";
import React, { useEffect, useState } from "react";
import OneAdvertisement from "./OneAdvertisement";

const AddAdvertisement = () => {
    const [imgVal, setImgVal] = useState(null);

    const [descriptionTxt, setDescriptionTxt] = useState("");

    const [renewNum, setRenewNum] = useState("");

    const [linkUrl, setLinkUrl] = useState("");

    const [isAddAdvertise, setIsAddAdvertise] = useState(false);

    const [traderName, setTraderName] = useState(""); // main category Name
    const [traderId, setTraderId] = useState("");
    const [tradersArr, setTradersArr] = useState([]);

    const [advertiseArray, setAdvirtiseArray] = useState([]);

    const [fetchAgain, setFetchAgain] = useState(false);

    useEffect(() => {
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        const getVendors = async () => {
            try {
                axios
                    .get("${process.env.MIX_APP_URL}/api/traders", {
                        headers: { Authorization: `Bearer ${getToken}` },
                    })
                    .then((res) => {
                        setTradersArr(res.data.data);
                        console.log(res.data.data);
                    });
            } catch (er) {
                console.log(er);
            }
        };
        getVendors();

        const getAdvertisements = async () => {
            try {
                axios
                    .get(
                        "${process.env.MIX_APP_URL}/api/advertisements"
                        // {
                        //     headers: { Authorization: `Bearer ${getToken}` },
                        // }
                    )
                    .then((res) => {
                        // setTradersArr(res.data.data);
                        console.log(res.data.data);
                        setAdvirtiseArray(res.data.data);
                        // setAdv(res.data.data[0]);
                    });
            } catch (er) {
                console.log(er);
            }
        };
        getAdvertisements();
    }, [fetchAgain]);

    const addAdvertisementFunc = async () => {
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        const formData = new FormData();
        formData.append("img", imgVal);
        formData.append("link", linkUrl);
        formData.append("renew", renewNum);
        formData.append("trader_id", traderId);
        formData.append("created_by", 1);
        formData.append("updated_by", 1);
        try {
            const res = await axios.post(
                `${process.env.MIX_APP_URL}/api/advertisements`,
                formData,
                {
                    headers: { Authorization: `Bearer ${getToken}` },
                }
            );

            setIsAddAdvertise(!isAddAdvertise);
            setImgVal(null);
            setRenewNum("");
            setTraderId("");
            setFetchAgain();
        } catch (error) {
            console.warn(error.message);
        }
    };

    const handleImg = (e) => {
        setImgVal(e.target.files[0]);
        console.log(e.target.files[0]);
        setIsAddAdvertise(!isAddAdvertise);
    };

    const handleTrader = (tr) => {
        setTraderName(tr.f_name);
        setTraderId(tr.id);
    };

    const refetch = () => setFetchAgain(!fetchAgain);

    return (
        <div dir="rtl" className="advertisement-div p-3">
            {/* <img
                src={`${process.env.MIX_APP_URL}/assets/images/uploads/advertisements/${adv.img}`}
                alt="no image"
            /> */}
            <div className="img-div my-3">
                <h3>اختر صوره للاعلان</h3>
                <input type="file" onChange={handleImg} />
            </div>
            <div className="img-div my-3">
                <h3>مدة الاعلان</h3>
                <input
                    className="py-2 px-3 border-2 border-slate-200 rounded-lg outline-none font-serif"
                    type="number"
                    value={renewNum}
                    onChange={(e) => setRenewNum(e.target.value)}
                />
            </div>
            <div className="img-div my-3">
                <h3>لينك الاعلان</h3>
                <input
                    className="py-2 px-3 border-2 border-slate-200 rounded-lg outline-none font-serif w-full"
                    type="text"
                    value={linkUrl}
                    onChange={(e) => setLinkUrl(e.target.value)}
                />
            </div>

            <FormControl
                className="form-select"
                sx={{ m: 1, minWidth: 160 }}
                size="small"
            >
                <InputLabel id="demo-select-small"> التجار</InputLabel>
                <Select
                    labelId="demo-select-small"
                    id="demo-select-small"
                    value={traderName}
                    label="التجار"
                >
                    {tradersArr &&
                        tradersArr.map((trader) => (
                            <MenuItem
                                key={trader.id}
                                onClick={() => handleTrader(trader)}
                                value={trader.f_name}
                            >
                                {`${trader.f_name} ${trader.m_name} ${trader.l_name}`}
                            </MenuItem>
                        ))}
                </Select>
            </FormControl>

            {isAddAdvertise && (
                <button
                    className="bg-green-500 rounded-md p-2 mt-2 text-white"
                    onClick={addAdvertisementFunc}
                >
                    إضافة الاعلان
                </button>
            )}

            <h1 className="m-3 text-center bg-green-300 p-2 rounded-sm  mt-5">
                الاعلانات
            </h1>
            {advertiseArray &&
                advertiseArray.map((advertise) => (
                    <div key={advertise.id} className="advertise-div">
                        <OneAdvertisement
                            key={advertise.id}
                            advertise={advertise}
                            refetch={refetch}
                        />
                    </div>
                ))}
        </div>
    );
};

export default AddAdvertisement;
