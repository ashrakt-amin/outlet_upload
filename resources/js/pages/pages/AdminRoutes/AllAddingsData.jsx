import React from "react";
import AllActivites from "./AllAddingDataCompon.jsx/AllActivites";
import AllCategories from "./AllAddingDataCompon.jsx/AllCategories";
import AllColors from "./AllAddingDataCompon.jsx/AllColors";
import AllDistributeCompanies from "./AllAddingDataCompon.jsx/AllDistributeCompanies";
import AllGroups from "./AllAddingDataCompon.jsx/AllGroups";
import AllImportedCompanies from "./AllAddingDataCompon.jsx/AllImportedCompanies";
import AllManufactories from "./AllAddingDataCompon.jsx/AllManufactories";
import AllSizes from "./AllAddingDataCompon.jsx/AllSizes";
import AllSubCategories from "./AllAddingDataCompon.jsx/AllSubCategories";
import AllType from "./AllAddingDataCompon.jsx/AllType";
import AllUnitsItemId from "./AllAddingDataCompon.jsx/AllUnitsItemId";
import AllVolumes from "./AllAddingDataCompon.jsx/AllVolumes";

const AllAddingsData = () => {
    return (
        <div>
            <AllCategories />
            <AllSubCategories />
            <AllActivites />
            <AllColors />
            <AllSizes />
            <AllVolumes />
            {/* <AllDistributeCompanies />
            <AllImportedCompanies />
            <AllManufactories /> */}
            <AllUnitsItemId />
        </div>
    );
};

export default AllAddingsData;
