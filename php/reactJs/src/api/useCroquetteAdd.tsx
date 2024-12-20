import { useState } from 'react';
import axios from 'axios';

// Hook personnalisé pour gérer la soumission du formulaire d'ajout de chat
const useCroquetteAdd = () => {
    const [isSuccess, setIsSuccess] = useState(false);
    const [error, setError] = useState<Error | null>(null);

    // Fonction de soumission du formulaire
    const send = async (marque: string, croquette: string) => {
        console.log(marque, croquette);
        try {
            // Les données à envoyer dans la requête POST
            const data = {
                "validate": false,
                "name": croquette,
                "brand": {
                    "name": marque,
                },
                "categories": [
                    {
                        "typePet": "chat"
                    }
                ]
            };

            // Effectue une requête POST vers l'API des chats
            const response = await axios.post('http://15.188.23.24:8642/api/v1/croquette/motion', data);

            if (response.status === 201) {
                // Chat ajouté avec succès
                setIsSuccess(true);
            }
        } catch (error) {
            setError(error as Error);
        }
    };

    // Retourner les valeurs et fonctions nécessaires
    return { isSuccess, error, send };
};

export default useCroquetteAdd;
