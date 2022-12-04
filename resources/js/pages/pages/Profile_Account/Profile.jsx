import axios from "axios";
import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const Profile = () => {
    const [isClient, setIsClient] = useState(false);

    const [clientInfo, setClientInfo] = useState({});

    const navigate = useNavigate();
    //1- if token in localstorage go to next step --> get token send request to get data:>.
    //2- make request with token to check client is true.
    //3- if true show data.

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();

        const getOneClient = async () => {
            axios.defaults.withCredentials = true;
            let getToken = JSON.parse(localStorage.getItem("clTk"));
                try {
                    await axios
                        .get(`https://abc-mansoura.com/api/items`,
                        // {
                        //     headers: {
                        //         Authorization: `Bearer ${getToken}`,
                        //     },
                        // }
                        )
                        .then(async (resp) => {
                            setClientInfo(resp.data.data);
                            console.log(resp);
                            if (resp.status == "200") {
                                setIsClient(true);
                            }
                            // if client login or has token get it and call api to get data
                        });
                } catch (er) {
                    console.log(er);
                }
        };
        getOneClient();
        return () => {
            cancelRequest.cancel();
        };
    }, []);

    return (
        <div>
            <div className="client-info-div flex flex-col items-center px-2">
                <h1 dir="rtl">مرحبا يا : {clientInfo?.f_name}</h1>
                {isClient && (
                    <div
                        dir="rtl"
                        style={{ maxWidth: "300px" }}
                        className=" shadow-md rounded-md flex-1 p-2 m-2"
                    >
                        <h1>الاسم الاول : {clientInfo?.f_name}</h1>
                        <h1>الاسم الاوسط: {clientInfo?.m_name}</h1>
                        <h1>الاسم الاخير: {clientInfo?.l_name}</h1>
                        <h1>الايمل: {clientInfo?.email}</h1>
                        <h1>رقم الهاتف: {clientInfo?.phone}</h1>
                    </div>
                )}
            </div>
        </div>
    );
};

export default Profile;
