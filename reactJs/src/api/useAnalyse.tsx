import { useState } from 'react';
import axios from 'axios';
import { FormValuesTypes } from '../hook/useFormValues'


export interface AnalyseDataResponseTypes {
    status: number;
    success: boolean;
    message: string;
    data: {
        marque: string;
        name: string;
        sterilise: boolean;
        energie_metabolisable: number; //(en_kcal/100g)
        besoin_energetique: number;
        analyse_quantitatif_nutriment: {
            proteine: boolean;
            lipide: boolean;
            ENA: boolean;
        };
        quantite_Journaliere: number;  //(en_g/jour)
        url: string;
        urlimage: string;
        element_nutritif: {
            ENA: number;
            proteine: number;
            lipide: number;
            fibre: number;
            cendres: number;
            eau: number;
        };
        score: number;
        commentaire: string;
    };
}

// Hook personnalisé pour gérer l'appel à l'API et la réponse
const useAnalyse = () => {

    // const [response, setResponse] = useState<AnalyseDataResponseTypes | null>(null);
    const [response, setResponse] = useState<AnalyseDataResponseTypes | {}>({});


    // Fonction asynchrone pour effectuer l'analyse en appelant l'API
    const getAnalyse = async (data: FormValuesTypes) => {
        try {
            const { croquette, ...parametre } = data;

            const response = await axios.post(
                "http://15.188.23.24:8642/api/v1/analyse/" + croquette,
                {
                    race: parametre.race,
                    stade: parametre.stade,
                    activite: parametre.activite,
                    morphologie: parametre.morphologie,
                    sterilite: parametre.sterilite,
                },
                {
                    headers: {
                        'Content-Type': 'application/json'
                    },
                }
            );

            setResponse(response.data);
        } catch (error) {
            console.error(error);
        }
    };

    // Fonction pour réinitialiser la réponse
    // const resetResponse = () => {
    //     setResponse(null);
    // };

    // Retourner la réponse de l'analyse, la fonction pour l'appel à l'API
    // et la fonction pour réinitialiser la réponse
    return { response, getAnalyse };
};

export default useAnalyse;
