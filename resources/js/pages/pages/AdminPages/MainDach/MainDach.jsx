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
import AddCategories from "./AddingInputs/AddCategories";

const MainDach = () => {
    // ------------------------ ( Toggle State ) -----------------------
    const [isAddGroup, setIsAddGroup] = useState(false);
    const [isAddSubCatg, setIsAddSubCatg] = useState(false);
    const [isTypes, setIsTypes] = useState(false);

    // ------------------------ ( Toggle State ) -----------------------

    // ------------------------ ( Success Message State ) -----------------------
    const [successMsg, setSuccessMsg] = useState("");
    // ------------------------ ( Success Message State ) -----------------------

    // const [allCategories, setAllCategories] = useState([]);
    // const [allSubCategory, setAllSubCategory] = useState([]);
    // const [allGroups, setAllGroups] = useState([]);
    // const [allTypes, setAllTypes] = useState([]);

    // ------------------------ (toggle modals) ---------------------
    const togleGender = () => setIsAddGroup(!isAddGroup);
    const togleSubCatg = () => setIsAddSubCatg(!isAddSubCatg);
    const togleTypes = () => setIsTypes(!isTypes);
    // ------------------------ (toggle modals) ---------------------

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

            <AddCategories />

            {/* ---------------------(Adding buttons)----------------  */}
            <div className="modals-btns flex flex-col gap-4 ">
                {/* <div className="adding-btns-div flex flex-col items-start gap-3">
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
                </div> */}
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
