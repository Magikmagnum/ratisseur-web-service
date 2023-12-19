import React, { useState } from 'react';

// Type pour les étapes
export type StepTypes = {
    step_croquette: boolean;
    step_race: boolean;
    step_parametre: boolean;
};

// Type pour le hook useStepTracker
export type TrackerStepType = {
    step: StepTypes;
    setStep: (key: keyof StepTypes, value: boolean) => void;
};

// État initial des étapes
const initialStep: StepTypes = {
    step_croquette: false,
    step_race: false,
    step_parametre: false,
};



// Hook personnalisé pour suivre les étapes
function useStepTracker(): { step: StepTypes; setStep: (key: keyof StepTypes, value: boolean) => void; } {

    // État pour suivre les étapes
    const [step, setStepFunction] = useState<StepTypes>(initialStep);

    // Fonction pour mettre à jour l'étape par clé
    const setStep = (key: keyof StepTypes, value: boolean) => {
        if (key in step) {
            const newStep = { ...step };
            newStep[key] = value;
            setStepFunction(newStep);
        }
    };

    // Retourner l'état des étapes et la fonction pour les mettre à jour
    return { step, setStep };
}

export default useStepTracker;