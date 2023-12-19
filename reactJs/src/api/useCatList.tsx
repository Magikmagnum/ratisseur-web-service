import React, { useState, useEffect } from 'react';
import axios from 'axios';

// Définir le type pour les données de chat
export interface CatTypes {
    value: string;
    key: string;
}

// Créer le hook custom pour obtenir la liste des chats
function useCatList() {
    const [catList, setCatList] = useState<CatTypes[]>([]);

    // Fonction asynchrone pour obtenir la liste des chats
    const getCatList = async () => {
        try {
            // Faire la requête GET à l'API pour obtenir les données des chats
            const response = await axios.get('http://15.188.23.24:8642/api/v1/cats');

            // Transformer les données de la réponse en format souhaité
            const catsSelect: CatTypes[] = response.data.data.map((cat: any) => ({
                value: cat.name,
                key: cat.id.toString(),
            }));

            // Mettre à jour la liste des chats dans le state
            setCatList(catsSelect);
        } catch (error) {
            console.error(error);
        }
    };

    // Utiliser useEffect pour appeler getCatList au montage initial du composant
    useEffect(() => {
        getCatList();
    }, []);

    // Retourner la liste des chats et la fonction pour la mettre à jour
    return { catList, setCatList };
}

export default useCatList;
