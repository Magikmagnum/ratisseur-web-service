
import * as React from 'react';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { TextField } from "@mui/material";
import { useField, useFormikContext } from "formik"




const DateField = React.memo(({ name, label, ...otherProps }: { name: string, label: string }) => {

    const [field, mata] = useField(name);
    const { setFieldValue } = useFormikContext();


    const configDatefield = {
        ...field,
        ...otherProps,
        fullWidth: true,
        error: false,
        helperText: "",

    };

    if (mata && mata.touched && mata.error) {
        configDatefield.error = true;
        configDatefield.helperText = mata.error;
    }

    return (
        <LocalizationProvider dateAdapter={AdapterDateFns}>
            <DatePicker
                label={label}
                value={field.value}
                inputFormat="dd-MM-yyyy"
                onChange={(newValue) => {
                    setFieldValue(name, newValue);
                }}
                renderInput={(params) => <TextField margin="normal" {...params} {...configDatefield} />}
            />
        </LocalizationProvider>
    );
})

export default DateField;