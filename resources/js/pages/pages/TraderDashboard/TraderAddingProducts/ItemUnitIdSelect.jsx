import { FormControl, InputLabel, MenuItem, Select } from "@mui/material";
import React from "react";

const ItemUnitIdSelect = ({ itemsUnitsArr, whatItem, itemUnitsName }) => {
    return (
        <div className="item-units-select-div p-2 rounded-md flex gap-1 flex-col">
            <span>اختر وحدة المنتج</span>
            <FormControl dir="ltr" size="larg" className="w-full">
                <InputLabel id="demo-select-small">اختر وحدة المنتج</InputLabel>
                <Select
                    labelId="demo-select-small"
                    id="demo-select-small"
                    value={itemUnitsName}
                    label="وحدة المنتج"
                >
                    {itemsUnitsArr &&
                        itemsUnitsArr.map((itemUnitId) => (
                            <MenuItem
                                key={itemUnitId.id}
                                onClick={() => whatItem(itemUnitId)}
                                value={itemUnitId.name}
                            >
                                {itemUnitId.name}
                            </MenuItem>
                        ))}
                </Select>
            </FormControl>
        </div>
    );
};

export default ItemUnitIdSelect;
