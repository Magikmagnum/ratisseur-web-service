// SelectField
import React from 'react';
import { FormControl, InputLabel, Select, MenuItem } from '@mui/material';
import { SelectChangeEvent } from '@mui/material/Select'; // Importez SelectChangeEvent ici
import { BrandTypes } from '../../api/useBrandList'; // Importez votre type BrandTypes
import { CroquetteTypes } from '../../api/useCroquetteList'; // Importez votre type BrandTypes

export type OptionType = BrandTypes | CroquetteTypes;

interface SelectProps {
    id: string;
    label: string;
    value: string;
    options: OptionType[];
    onChange: (event: SelectChangeEvent<string>, child: React.ReactNode) => void; // Ajustement ici
    index: boolean | string;
}

const SelectField: React.FC<SelectProps> = ({ id, label, value, options, onChange, index = false }) => {
    return (
        <FormControl fullWidth>
            <InputLabel id={`${id}-label`}>{label}</InputLabel>
            <Select
                labelId={`${id}-label`}
                id={id}
                value={value}
                label={label}
                onChange={onChange}
                fullWidth
            >
                {/* Options pour le champ select */}
                {options.map((option) => (
                    <MenuItem key={option.key} value={index !== false ? option.key : option.value}>
                        {option.value}
                    </MenuItem>
                ))}
            </Select>
        </FormControl>
    );
};

export default SelectField;



// const newValue =
// ('target' in event ? event.target.value : event) as string;

// setFormData({ ...formData, [fieldName]: newValue });


// if ('target' in event) {
//     setFormData({ ...formData, [fieldName]: event.target.value });
//   } else {
//     setFormData({ ...formData, [fieldName]: event.value });
//   }