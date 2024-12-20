import React, { useState, useEffect } from 'react';
import { Button, Alert, Stack, TextField, Checkbox, FormControlLabel, FormGroup } from '@mui/material/';

// Composant personnaliser
import SelectField from "./SelectField";
import useBrandList from '../../api/useBrandList';
import useCroquetteList from '../../api/useCroquetteList';
import useCroquetteAdd from '../../api/useCroquetteAdd';
import { FormTypes } from '../../hook/useFormValues';
import { TrackerStepType } from '../../hook/useStepTracker';
import lang_fr from '../../lang/fr';

// Définition des types des props attendues par le composant
interface InputCroquetteProps {
    trackerStep: TrackerStepType,
    formAdmin: FormTypes,
}


const FieldCroquetteComponent: React.FC<InputCroquetteProps> = ({
    trackerStep,
    formAdmin,
}) => {

    const { step, setStep } = trackerStep;
    const { formData, setFormData, resetFormData } = formAdmin;

    // État local pour gérer l'état de la case à cocher
    const [isChecked, setIsChecked] = useState(false);
    const croquetteList = useCroquetteList(formData.marque, isChecked);
    const { isSuccess, error, send } = useCroquetteAdd();
    // Utiliser le hook pour obtenir la liste des marques et croquettes
    const { brandList } = useBrandList();

    useEffect(() => {
        if (isSuccess) {
            setTimeout(() => {
                resetFormData(['race', 'marque', 'croquette']);
                setIsChecked(false);
                setStep('step_croquette', false);
            }, 1000);
        }
    }, [isSuccess])

    return (
        <>
            {/* Affiche le SelectField si la case à cocher est cochée */}
            {(!isChecked && !step.step_croquette) && (
                <>
                    <div className="title titleCard">
                        {lang_fr.selectionner_marque_croquette /* Les nutriments dont votre chat a besoin */}
                    </div>
                    {/* Champ select pour la marque de race */}
                    <SelectField
                        id="marque-select"
                        label="Marque"
                        value={formData.marque}
                        options={brandList}
                        onChange={(event) => setFormData('marque', event.target.value)}
                        index={false}
                    />


                    {(!isChecked && formData.marque) && (
                        <>
                            {/* Champ select pour la croquette de race */}
                            <SelectField
                                id="croquette-select"
                                label="Croquette"
                                value={formData.croquette}
                                options={croquetteList}
                                onChange={(event) => setFormData('croquette', event.target.value)}
                                index={true}
                            />

                            {/* Affiche le Bouton si la case à cocher est cochée */}
                            {(!isChecked && formData.croquette) && (
                                <>
                                    <Button type="submit" variant="contained" color="primary" className='button-form' onClick={() => setStep('step_croquette', true)}>
                                        Suivant
                                    </Button>
                                </>
                            )}
                        </>
                    )}
                </>
            )}
            {/* Affiche le TextField si la case à cocher est cochée */}
            {(isChecked && !step.step_croquette) && (
                <>
                    <div className="title titleCard">
                        {lang_fr.inserer_marque_croquette /* Les nutriments dont votre chat a besoin */}
                    </div>

                    <Stack sx={{ width: '100%' }} spacing={2}>
                        {(error) && (
                            <Alert severity="error">{lang_fr.error_message}</Alert>
                        )}
                        {(isSuccess) && (
                            <Alert severity="success">{lang_fr.success_message}</Alert>
                        )}
                    </Stack>

                    <TextField
                        id="marque-input"
                        variant="outlined"
                        label="Marque"
                        onChange={(event) => { setFormData('marque', event.target.value) }}
                        value={formData.marque}
                        fullWidth />

                    <TextField
                        id="croquette-input"
                        variant="outlined"
                        label="Croquette"
                        onChange={(event) => { setFormData('croquette', event.target.value) }}
                        value={formData.croquette}
                        fullWidth />
                </>
            )}
            {/* Case à cocher pour activer ou désactiver les champs précédents */}
            {(!step.step_croquette) && (
                <>
                    <FormGroup>
                        <FormControlLabel
                            control={<Checkbox checked={isChecked} onChange={(event) => setIsChecked(event.target.checked)} />}
                            label={!formData.marque ? "La marque manque dans la liste ?" : "La croquette manque dans la liste ?"}
                        />
                    </FormGroup>
                </>
            )}

            {/* Affiche le Bouton si la case à cocher est cochée */}
            {(isChecked && !step.step_croquette) && (
                <>
                    {/* Bouton pour soumettre le formulaire */}
                    <Button type="submit" variant="contained" color="primary" className='button-form' onClick={() => { send(formData.marque, formData.croquette) }}>
                        Envoyer
                    </Button>
                </>
            )}
        </>
    );
}

export default FieldCroquetteComponent;

