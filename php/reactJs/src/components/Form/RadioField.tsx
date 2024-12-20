import * as React from 'react';
import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel } from "@mui/material";

import { useField, ErrorMessage } from "formik"


type ItemRadio = { key: any, value: any }

export function RadioField({ name, label, data, ...otherProps }: { name: string, label: string, data: Array<ItemRadio> }) {


    const [field] = useField(name);


    const configCheckbox = {
        ...field,
        ...otherProps,
    };

    return (
        <FormControl>
            <FormLabel id={name + "id"}>{label}</FormLabel>
            <RadioGroup
                aria-labelledby={name + "id"}
                name={name}
                value={field.value}
            >
                {data.map(item => <FormControlLabel
                    {...configCheckbox}
                    key={item.key} value={item.value} control={<Radio />} label={item.key} />)}
            </RadioGroup>
        </FormControl>
    );
}
//<ErrorMessage name={name} className="textComment textDanger" />