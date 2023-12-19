import * as React from 'react';
import './Form.scss';
import { TextField, MenuItem, Box, Radio, RadioGroup, FormControlLabel, FormControl, FormLabel, Typography, Rating, InputLabel, FormHelperText } from "@mui/material";
import Select, { SelectChangeEvent } from '@mui/material/Select';
import FormGroup from '@mui/material/FormGroup';
import Checkbox from '@mui/material/Checkbox';

import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import { DesktopDateRangePicker } from '@mui/x-date-pickers-pro/DesktopDateRangePicker';
import { DateRange } from '@mui/x-date-pickers-pro/DateRangePicker';

import { Field, ErrorMessage, } from 'formik';
import { useField } from "formik"


export const FormInput = React.memo(({ label, name, type = "text", placeholder = "", formik, getIn }:
    {
        label: string,
        name: string,
        placeholder?: string,
        type?: string,

        formik?: any,
        getIn?: any
    }) => {

    const error = getIn(formik.errors, name);
    const touched = getIn(formik.touched, name);

    return (
        <div className="form">
            <label className="formLabel" htmlFor={name}>{label}</label>
            <input className="formImput" type={type} id={name} placeholder={placeholder}
                {...formik.getFieldProps(name)}
            />
            {touched && error && <span className='text textComment textDanger'>{error}</span>}
        </div>
    )
})





export const FormButton = ({ value = "Enter", onClick = null, disabled = false }: { value?: string, onClick?: Function | null, disabled?: boolean }) => {
    return (
        <button className="formBtn" disabled={disabled} onClick={() => (onClick == null) ? null : onClick()}>{value}</button>
        // <input type="submit" value={value} onClick={() => (onClick == null) ? null : onClick()} className="formBtn" disabled={disabled} />
    )
}

export const InputForm = ({ label, name, type = "text", placeholder = "" }:
    {
        label: string,
        name: string,
        type?: string,
        placeholder?: string
    }) => {
    return (
        <>
            <div className="form">
                <label className="formLabel" htmlFor={name}>{label}</label>
                <Field name={name} className="formImput" type={type} id={name} placeholder={placeholder}>
                </Field>
                <ErrorMessage className="text textComment textDanger" component={"span"} name={name}></ErrorMessage>
            </div>
        </>
    )
}


type ItemSelect = { key: any, value: any }

export const SelectField = React.memo(({ label, data, onClick = null, helperText = '' }: { label: string, data: Array<ItemSelect>, onClick?: Function | null, helperText?: string }) => {
    const [value, setValue] = React.useState('');

    const handleChange = (event: SelectChangeEvent) => {
        setValue(event.target.value as string);
    };
    return (

        <FormControl fullWidth>
            <InputLabel id="demo-simple-select-label">{label}</InputLabel>
            <Select
                labelId="demo-simple-select-label"
                id="demo-simple-select"
                value={value}
                label={label}
                onChange={handleChange}
                //onClick={() => (onClick == null) ? null : onClick()}
                //size='small'
                fullWidth
                //helperText={helperText}
                error={false}
            >
                {data.map((item) => <MenuItem key={item.key} value={item.value}>{item.key}</MenuItem>)}
            </Select>
        </FormControl>
    )
})

export const DateField = React.memo(() => {
    const [value, setValue] = React.useState<Date | null>(null);

    return (
        <LocalizationProvider dateAdapter={AdapterDateFns}>
            <DatePicker
                label="Basic example"
                value={value}
                onChange={(newValue) => {
                    setValue(newValue);
                }}
                renderInput={(params) => <TextField fullWidth margin="normal" {...params} />}
            />
        </LocalizationProvider>
    );
})



export const DatePlageSelectField = React.memo(() => {
    const [value, setValue] = React.useState<DateRange<Date>>([null, null]);

    return (
        <LocalizationProvider
            dateAdapter={AdapterDateFns}
            localeText={{ start: 'Début', end: 'Fin' }}
        >
            <DesktopDateRangePicker
                value={value}
                onChange={(newValue) => {
                    setValue(newValue);
                }}
                renderInput={(startProps, endProps) => (
                    <React.Fragment>
                        <TextField fullWidth margin="normal" {...startProps} />
                        <Box sx={{ mx: 2 }}> Et </Box>
                        <TextField fullWidth margin="normal" {...endProps} />
                    </React.Fragment>
                )}
            />
        </LocalizationProvider>
    );
})



export function RadioField({ name, label, data, ...otherProp }: { name: string, label: string, data: Array<ItemSelect> }) {
    const [value, setValue] = React.useState('female');

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setValue((event.target as HTMLInputElement).value);
    };

    return (
        <FormControl>
            <FormLabel id={name + "id"}>{label}</FormLabel>
            <RadioGroup
                aria-labelledby={name + "id"}
                name={name}
                value={value}
                onChange={handleChange}
            >
                {data.map(item => <FormControlLabel key={item.key} value={item.value} control={<Radio />} label={item.key} />)}
            </RadioGroup>
        </FormControl>
    );
}



export function RatingField() {
    const [value, setValue] = React.useState<number | null>(2);
    // Rating components option is : (name="disabled"), (value={null}), readOnly
    return (
        <Box
            sx={{
                '& > legend': { mt: 2 },
            }}
        >
            <Typography component="legend">Controlled</Typography>
            <Rating
                name="simple-controlled"
                value={value}
                onChange={(event, newValue) => {
                    setValue(newValue);
                }}
            />
        </Box>
    );
}

export function CheckboxField() {

    const [state, setState] = React.useState({
        gilad: true,
        jason: false,
        antoine: false,
    });

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setState({
            ...state,
            [event.target.name]: event.target.checked,
        });
    };

    const { gilad, jason, antoine } = state;
    const error = [gilad, jason, antoine].filter((v) => v).length !== 2;



    return (
        <FormControl required
            error={error}
            component="fieldset"
            variant="standard"
        >
            <FormLabel component="legend">Assign responsibility</FormLabel>
            <FormGroup>
                <FormControlLabel control={<Checkbox checked={gilad} onChange={handleChange} name="gilad" />} label="Label" />
                <FormControlLabel control={<Checkbox checked={jason} onChange={handleChange} name="jason" />} label="Label" />
                <FormControlLabel control={<Checkbox checked={antoine} onChange={handleChange} name="antoine" />} label="Label" />
            </FormGroup>
            <FormHelperText>You can display an error</FormHelperText>
        </FormControl>
    );
}


export const InputField = ({ name, label, ...otherProps }: { name: string, label: string }) => {

    //const [field, mata] = useField(name);

    const configTextfield = {
        //...field,
        ...otherProps,
        fullWidth: true,
        error: false,
        helperText: "",
        // variant: "outlined",
    };

    // if (mata && mata.touched && mata.error) {
    //     configTextfield.error = true;
    //     configTextfield.helperText = mata.error;
    // }

    return (
        <TextField label={label} {...configTextfield} />
    )
}