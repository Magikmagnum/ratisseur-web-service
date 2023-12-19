import React, { useState } from 'react';

import { SelectChangeEvent } from '@mui/material/Select';
import { Button } from '@mui/material/';

import SelectField, { OptionType } from "../../components/Form/SelectField";
import HeaderWithCallbackComponent from "../../components/Header/HeaderWithCallbackComponent";

import useBrandList from '../../api/useBrandList';


import { activityList } from "../../helpers/activity_list";
import { ageList } from "../../helpers/age_list";
import { morphplogieList } from "../../helpers/morphplogie_list";
import { steriliseList } from "../../helpers/sterilise_list";


import { FormTypes } from '../../hook/useFormValues';
import { TrackerStepType } from '../../hook/useStepTracker';

import { raceList } from "../../helpers/race_list";

import lang_fr from '../../lang/fr';


// Définition des types des props attendues par le composant

// Définition des types des props attendues par le composant
interface InputCaractaireProps {
    trackerStep: TrackerStepType,
    formAdmin: FormTypes,
}

const FieldCaractaireComponent: React.FC<InputCaractaireProps> = ({
    formAdmin,
    trackerStep,
}) => {

    const { step, setStep } = trackerStep;
    const { formData, setFormData, resetFormData } = formAdmin;

    // État local pour gérer l'état de la case à cocher
    const [isChecked, setIsChecked] = useState(false);

    // Gestionnaire de changement pour la case à cocher
    const handleCheckboxChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setIsChecked(event.target.checked);
    };



    return (
        <>
            {step.step_race && (
                <>
                    <HeaderWithCallbackComponent title={lang_fr.selectionner_caractére_chat} onReset={() => setStep('step_race', false)} />

                    {/* Champ select pour la ligne de âge */}
                    < SelectField
                        id="age-select"
                        label="Age"
                        value={formData.stade}
                        options={ageList}
                        onChange={(event) => setFormData('stade', event.target.value)}
                        index={false}
                    />

                    {/* Champ select pour la ligne de Sterilite */}
                    <SelectField
                        id="sterilite-select"
                        label="Sterilite"
                        value={formData.sterilite}
                        options={steriliseList}
                        onChange={(event) => setFormData('sterilite', event.target.value)}
                        index={false}
                    />
                    {/* Champ select pour la ligne de activité */}
                    <SelectField
                        id="active-select"
                        label="Active"
                        value={formData.activite}
                        options={activityList}
                        onChange={(event) => setFormData('activite', event.target.value)}
                        index={false}
                    />
                    {/* Champ select pour la ligne de croquette */}
                    <SelectField
                        id="morphologie-select"
                        label="Morphologie"
                        value={formData.morphologie}
                        options={morphplogieList}
                        onChange={(event) => setFormData('morphologie', event.target.value)}
                        index={false}
                    />

                    {/* Bouton pour soumettre le formulaire */}
                    <Button type="submit" variant="contained" color="primary" className='button-form'>
                        Analyser
                    </Button>
                </>
            )}
        </>
    );
}

export default FieldCaractaireComponent;

