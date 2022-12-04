import axios from "axios";
import React from "react";
import { useEffect, useState } from "react";
import AddTrader from "../../../Modals/AddTrader";
import { Button, Menu, MenuItem } from "@mui/material";
import pagination from "../../../Pagination/Pagintion";

import { BiEdit } from "react-icons/bi";
import { RiDeleteBin2Line } from "react-icons/ri";
import { Link } from "react-router-dom";
import UpdateTrader from "../../../Modals/UpdateTrader/UpdateTrader";

const VendrosPage = () => {
    const [tradersArr, setTradersArr] = useState([]);

    const [traderInf, setTraderInf] = useState({});

    //----------------- Pagination state -----------------
    const [currentPage, setCurrentPage] = useState(1);
    const [customersPerPages, setCustomersPerPages] = useState(10);
    //----------------- Pagination state -----------------

    const [deleteNowBtn, setDeleteNowBtn] = useState(false);

    const [opnTraderModal, setOpnTraderModal] = useState(false);
    const [isUpdtModal, setIsUpdtModal] = useState(false);

    // -------------------- modal toggle ---------------------------
    const traderModal = () => setOpnTraderModal(!opnTraderModal);
    const opnUpdtModal = (traderinfo) => {
        setIsUpdtModal(!isUpdtModal);
        setTraderInf(traderinfo);
    };
    // -------------------- modal toggle ---------------------------

    const [fetchAgain, setFetchAgain] = useState(false);

    useEffect(() => {
        let getToken = JSON.parse(localStorage.getItem("uTk"));
        const getVendors = async () => {
            try {
                axios
                    .get(
                        `${process.env.MIX_APP_URL}/api/traders`
                        // , {
                        //     headers: { Authorization: `Bearer ${getToken}` },
                        // }
                    )
                    .then((res) => {
                        setTradersArr(res.data.data);
                        // console.log(res.data.data);
                    });
            } catch (er) {
                console.log(er);
            }
        };

        getVendors();
    }, [fetchAgain]);

    //------------------------ Pagintion vars ------------------------
    const lastCustIndx = currentPage * customersPerPages; // 1 * 10 => 10
    const firstCustIndx = lastCustIndx - customersPerPages; // 10 - 10
    const currentRange = tradersArr.slice(firstCustIndx, lastCustIndx);
    let num = pagination(tradersArr.length, customersPerPages);
    //------------------------ Pagintion vars ------------------------

    console.log(currentRange);

    const showConfirm = () => {
        setDeleteNowBtn(!deleteNowBtn);
    };

    const deleteNowFunc = async (traderid) => {
        try {
            axios
                .delete(`${process.env.MIX_APP_URL}/api/traders/${traderid}`)
                .then((res) => {
                    console.log(res);
                });
        } catch (error) {}
    };

    const refetchTraders = () => {
        setFetchAgain(!fetchAgain);
    };

    return (
        <>
            <h1 className="text-center font-bold my-3 text-xl border-4 p-3 ">
                التجار
            </h1>

            {opnTraderModal && (
                <AddTrader
                    getTradersAgain={refetchTraders}
                    closeModal={traderModal}
                />
            )}
            {isUpdtModal && (
                <UpdateTrader
                    traderInfo={traderInf}
                    closeModal={opnUpdtModal}
                />
            )}

            <div className="flex justify-center my-4">
                <button
                    onClick={traderModal}
                    className="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                >
                    إضافة تاجر جديد
                </button>
            </div>

            <div className="px-6 py-3 w-full">
                <table className="w-full text-gray-500 dark:text-gray-400">
                    <thead className="text-sm md:text-md text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" className="py-6 px-6">
                                id
                            </th>

                            <th scope="col" className="py-6 px-6">
                                <span>اسم التاجر</span>
                            </th>

                            <th scope="col" className="py-6 px-6">
                                <span>تعديل</span>
                            </th>
                            <th scope="col" className="py-6 px-6">
                                <span>حذف</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {currentRange.map((tradr) => (
                            <tr
                                className="bg-white my-3 border-b dark:bg-gray-800 dark:border-gray-700 text-center"
                                key={tradr.id}
                            >
                                <td>{tradr.id}</td>
                                <td>
                                    <Link
                                        className="border-2 border-blue-800 p-2 rounded-md"
                                        to={`/dachboard/vendors/${tradr.id}`}
                                    >
                                        {tradr.f_name}
                                    </Link>
                                </td>
                                <td className="py-4 px-6">
                                    <button
                                        onClick={() => opnUpdtModal(tradr)}
                                        className="flex mx-auto items-center gap-1 text-yellow-300 hover:bg-yellow-300 px-2 rounded-md hover:text-white transition-all duration-900 ease-in-out"
                                    >
                                        تعديل <BiEdit />
                                    </button>
                                </td>
                                {deleteNowBtn && (
                                    <td className="py-4 px-6">
                                        <button
                                            onClick={() =>
                                                deleteNowFunc(tradr.id)
                                            }
                                            className="flex mx-auto items-center gap-1 bg-red-600 px-3 text-white rounded-md transition-all duration-900 ease-in-out"
                                        >
                                            تأكيد الحذف <RiDeleteBin2Line />
                                        </button>
                                    </td>
                                )}

                                {!deleteNowBtn && (
                                    <td className="py-4 px-6">
                                        <button
                                            onClick={showConfirm}
                                            className="flex mx-auto items-center gap-1 text-red-500 hover:bg-red-600 px-3 hover:text-white rounded-md transition-all duration-900 ease-in-out"
                                        >
                                            حذف <RiDeleteBin2Line />
                                        </button>
                                    </td>
                                )}
                            </tr>
                        ))}
                    </tbody>
                </table>
                <div className="mt-4 p-1 flex flex-wrap">
                    {num.map((el) => (
                        <Button
                            style={{
                                background: `${
                                    el == currentPage ? "black" : ""
                                }`,
                                margin: "0 5px",
                                fontWeight: "bold",
                            }}
                            key={el}
                            variant="outlined"
                            onClick={() => setCurrentPage(el)}
                        >
                            {el}
                        </Button>
                    ))}
                </div>
            </div>
        </>
    );
};

export default VendrosPage;
