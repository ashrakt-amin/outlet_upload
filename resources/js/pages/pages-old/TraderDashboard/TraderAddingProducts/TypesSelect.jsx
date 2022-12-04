import { FormControl, InputLabel, MenuItem, Select } from "@mui/material";
import React from "react";
import { useState } from "react";

const TypesSelect = ({ handleCategg, typesArray, typeName }) => {
    // const [typeName, setTypeName] = useState("");

    return (
        <div className="types-div">
            <div>إسم التصنيف</div>
            <FormControl
                dir="ltr"
                sx={{ m: 1, maxWidth: "65%" }}
                size="larg"
                className="w-full"
            >
                <InputLabel id="demo-select-small">التصنيفات</InputLabel>
                <Select
                    labelId="demo-select-small"
                    id="demo-select-small"
                    value={typeName}
                    label="التصنيفات الفرعية"
                >
                    {typesArray &&
                        typesArray.map((type) => (
                            <MenuItem
                                key={type.id}
                                onClick={() => handleCategg(type)}
                                value={type.name}
                            >
                                {type.name}
                            </MenuItem>
                        ))}
                </Select>
            </FormControl>
        </div>
    );
};

export default TypesSelect;
