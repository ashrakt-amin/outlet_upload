import axios from "axios";
import { useState, useEffect } from "react";
import { Link } from "react-router-dom";

import "./maindash.scss";
import AddGroupsModal from "./AdminModalsjs/AddGroupsModal";
import AddSubCatgModal from "./AdminModalsjs/AddSubCatgModal";
import AddTypesModal from "./AdminModalsjs/AddTypesModal";
import AddColors from "./AddingInputs/AddColors";
import AddSize from "./AddingInputs/AddSize";
import AddManufactories from "./AddingInputs/AddManufactories";
import AddDistributCompany from "./AddingInputs/AddDistributCompany";
import AddImportedCompany from "./AddingInputs/AddImportedCompany";
import AddItemUnits from "./AddingInputs/AddItemUnits";
import AddActivity from "./AddingInputs/AddActivity";
import AddVolume from "./AddingInputs/AddVolume";

const MainDach = () => {
    // ------------------------ ( Toggle State ) -----------------------
    const [isAddGroup, setIsAddGroup] = useState(false);
    const [isAddSubCatg, setIsAddSubCatg] = useState(false);
    const [isTypes, setIsTypes] = useState(false);
    const [isAddCategory, setIsAddCategory] = useState(false);
    // ------------------------ ( Toggle State ) -----------------------

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // const [allCategories, setAllCategories] = useState([]);
    // const [allSubCategory, setAllSubCategory] = useState([]);
    // const [allGroups, setAllGroups] = useState([]);
    // const [allTypes, setAllTypes] = useState([]);

    const [categorryVal, setCategorryVal] = useState("");

    const [categorryMsg, setCategorryMsg] = useState("");

    //--------------------------------------- ("Get All Categories") ---------------------------------------\\
    useEffect(() => {
        const cancelRequest = axios.CancelToken.source();

        return () => {
            cancelRequest.cancel();
        };
    }, []);
    //--------------------------------------- (" End Get All Categories") ---------------------------------------\\

    //--------------------------------------- ("Adding Category Function") ---------------------------------------\\
    const addCategorry = async () => {
        if (categorryVal.length > 0) {
            setIsAddCategory(!isAddCategory);
            try {
                axios
                    .post(`${process.env.MIX_APP_URL}/api/categories`, {
                        name: categorryVal,
                    })
                    .then((res) => {
                        setCategorryMsg(`${res.data.data.name}`);
                        setIsAddCategory(!isAddCategory);
                        setCategorryVal("");
                        setTimeout(() => {
                            setCategorryMsg("");
                        }, 5000);
                    });
            } catch (er) {
                setCategorryMsg(er.response.data.message);
                setTimeout(() => {
                    setCategorryMsg("");
                }, 5000);
            }
        }
    };
    //--------------------------------------- ("Adding Category Function") ---------------------------------------\\

    // ------------------------ (toggle modals) ---------------------
    const togleGender = () => setIsAddGroup(!isAddGroup);
    const togleSubCatg = () => setIsAddSubCatg(!isAddSubCatg);
    const togleTypes = () => setIsTypes(!isTypes);
    // ------------------------ (toggle modals) ---------------------

    const genderMsg = (msg) => {
        setSuccessMsg(msg);
        setTimeout(() => {
            setSuccessMsg("");
        }, 5000);
    };

    return (
        <div className="p-2" dir="rtl">
            {isAddSubCatg && <AddSubCatgModal togleSubCatg={togleSubCatg} />}
            {isAddGroup && <AddGroupsModal togleGender={togleGender} />}
            {isTypes && <AddTypesModal togleTypes={togleTypes} />}
            {successMsg.length > 0 && (
                <div className="msg fixed top-20 left-0 w-full rounded-md p-2 bg-green-400">
                    {successMsg}
                </div>
            )}
            {categorryMsg.length > 0 && (
                <div
                    className="category-msg w-full p-3 text-center text-lg
                 text-white fixed top-15 z-10 bg-green-400"
                >
                    {categorryMsg}{" "}
                </div>
            )}

            <h1>الصفحة الرئيسة</h1>
            <div className="project-grid grid grid-cols-5 gap-2 rounded-md">
                <div className="card bg-blue-400 hover:bg-sky-600 rounded-md">
                    <Link
                        className="w-full h-full p-3 block"
                        to={`/dachboard/projects`}
                    >
                        المشاريع
                    </Link>
                </div>
            </div>

            <h1 className="bg-slate-300 rounded-md p-2 my-3 text-center text-xl">
                إضافة التصنيفات
            </h1>
            <h1 className="bg-blue-600 rounded-md text-center  p-2 my-5 text-white">
                إضافة التصنيف الاساسى
            </h1>
            <div className="addCategorry-div mx-3 flex gap-3  items-center border-2 my-5 p-3 rounded-md">
                <span>التصنيف الأساسى</span>
                <input
                    type="text"
                    value={categorryVal}
                    placeholder="مثال : موضة - اجهزة إلكترونية"
                    className="border-none rounded-lg shadow-md my-3"
                    onChange={(e) => setCategorryVal(e.target.value)}
                />
                {!isAddCategory ? (
                    <button
                        onClick={() => setIsAddCategory(!isAddCategory)}
                        className="bg-blue-600 rounded-md p-2 my-3 text-white"
                    >
                        إضافة التصنيف
                    </button>
                ) : (
                    <span>
                        <button
                            onClick={addCategorry}
                            className="bg-green-500 rounded-md p-2 my-3 text-white"
                        >
                            تأكيد إضافة التصنيف
                        </button>
                        <button
                            onClick={() => setIsAddCategory(!isAddCategory)}
                            className="bg-red-600 mx-1 rounded-md p-2 text-white"
                        >
                            إلغاء
                        </button>
                    </span>
                )}
            </div>

            <div className="modals-btns flex flex-col gap-4 ">
                {/* ---------------------(Adding buttons)----------------  */}
                <div className="adding-btns-div flex flex-col items-start gap-3">
                    <button
                        onClick={togleSubCatg}
                        className="bg-blue-600 rounded-md p-2 text-white"
                    >
                        إضافة تصنيف فرعى
                    </button>
                    <button
                        onClick={togleGender}
                        className="bg-blue-600 rounded-md p-2 text-white"
                    >
                        إضافة مجموعة التصنيف الفرعى
                    </button>
                    <button
                        onClick={togleTypes}
                        className="bg-blue-600 rounded-md p-2 text-white"
                    >
                        إضافة داخل مجموعة التصنيف
                    </button>
                </div>
                {/* ---------------------(Adding buttons)----------------  */}

                {/*---------------------- Activity ----------------------*/}
                <AddActivity />
                {/*---------------------- Activity ----------------------*/}

                {/*------------------- importers الوحدة للمنتد ----------------------*/}
                <AddItemUnits />
                {/*------------------- importers الوحدة للمنتد ----------------------*/}

                {/*------------------- Colors ----------------------*/}
                <AddColors />
                {/*------------------- Colors ----------------------*/}

                {/*------------------- Size ----------------------*/}
                <AddSize />
                {/*------------------- Size ----------------------*/}

                {/*------------------- Size ----------------------*/}
                <AddVolume />
                {/*------------------- Size ----------------------*/}

                {/*------------------- manufactories الشركة المصنعة  ----------------------*/}
                <AddManufactories />
                {/*------------------- manufactories الشركة المصنعة  ----------------------*/}

                {/*------------------- companies الشركة الموزعة  ----------------------*/}
                <AddDistributCompany />
                {/*------------------- companies الشركة الموزعة  ----------------------*/}

                {/*------------------- importers الشركة المستوردة  ----------------------*/}
                <AddImportedCompany />
                {/*------------------- importers الشركة المستوردة  ----------------------*/}
            </div>
        </div>
    );
};

export default MainDach;
