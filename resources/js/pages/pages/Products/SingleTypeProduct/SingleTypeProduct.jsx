import { TextField } from "@mui/material";
import axios from "axios";
import React, { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import OneTypeItem from "./OneTypeItem";
import OneTypeItemProduct from "./OneTypeItemProduct";

const SingleTypeProduct = () => {
    const { id } = useParams();
    const navigate = useNavigate("");

    const [typsArray, setTypesArray] = useState([]);

    const [groupsArray, setGroupsArray] = useState([]);

    const [fetchdata, setFetchdata] = useState(false);

    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();
        const getGroups = async () => {
            try {
                const res = await axios.get(
                    `${process.env.MIX_APP_URL}/api/groups/${id}`,
                    {
                        cancelRequest: cancelRequest.token,
                    }
                );
                setTypesArray(res.data.data.types);
                console.log(res.data.data.types);
            } catch (error) {
                console.warn(error.message);
            }
        };
        getGroups();
    }, [fetchdata]);

    // (------------------------ handle item unints select ----------------------)
    const whatItem = (e) => {
        navigate(`/products/types/${e.id}`);
        console.log(e);
    };
    // (------------------------ handle item unints select ----------------------)

    const refechData = () => setFetchdata(!fetchdata);

    console.log("single type");

    return (
        <div dir="rtl" className="gird-products pb-16 gap-4 my-4 px-3">
            {/* <div dir="rtl" className="select-type-div p-2">
                <div className="text-sm">إختر التصنيف</div>
                <TextField
                    id="standard-select-currency-native"
                    select
                    className="w-28"
                    SelectProps={{
                        native: true,
                    }}
                    variant="standard"
                >
                    {groupsArray &&
                        groupsArray.map((itemUnit) => (
                            <option
                                key={itemUnit.id}
                                value={itemUnit.name}
                                onClick={() => whatItem(itemUnit)}
                            >
                                {itemUnit.name}
                            </option>
                        ))}
                </TextField>
            </div> */}
            <div>
                {/* {typsArray &&
                    typsArray.map((itm) => (
                        <div key={itm.id}>
                            {itm.items.length > 0 ? "" : "لا يوجد"}
                            {itm.name}
                        </div>
                    ))} */}
                {typsArray &&
                    typsArray.map((item) => (
                        <div key={item.id}>
                            <h1 className="shadow-md text-center p-3 m-3 rounded-md">
                                {item.name}
                            </h1>
                            <div className="gird-products flex flex-wrap justify-center pb-16 gap-4 my-4">
                                {item.items
                                    ? item.items.map((it) => (
                                          <OneTypeItemProduct
                                              key={it.id}
                                              oneItem={it}
                                              fetchAgain={refechData}
                                          />
                                      ))
                                    : "لا يوجد"}
                            </div>
                        </div>
                    ))}
            </div>
        </div>
    );
};

export default SingleTypeProduct;
