import { useState } from 'react';

export interface FormValuesTypes {
    race: string;
    marque: string;
    croquette: string;
    stade: string;
    morphologie: string;
    sterilite: string;
    activite: string;
}

const initialValues: FormValuesTypes = {
    race: '',
    marque: '',
    croquette: '',
    stade: '',
    morphologie: '',
    sterilite: '',
    activite: '',
};


// Type pour le hook useFormValues
export type FormTypes = {
    formData: FormValuesTypes;
    setFormData: (key: string, value: string) => void;
    resetFormData: (valuesToReset?: (keyof FormValuesTypes)[]) => void;
};

const useFormValues = () => {

    const [formData, setDataForm] = useState<FormValuesTypes>(initialValues);

    const resetFormData = (valuesToReset?: (keyof FormValuesTypes)[]) => {
        const resetValues = formData;

        if (valuesToReset) {
            for (const key of valuesToReset) {
                resetValues[key] = initialValues[key];
            }

            setDataForm((prevFormData) => ({
                ...prevFormData,
                ...resetValues,
            }));
        }
    };

    const setFormData = (key: string, value: string) => {
        setDataForm({ ...formData, [key]: value });
    };

    return { formData, setFormData, resetFormData };
};

export default useFormValues;
