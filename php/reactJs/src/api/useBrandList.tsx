import React, { useState, useEffect } from 'react';
import axios from 'axios';

// Définir le type pour les données de marque
export interface BrandTypes {
    value: string;
    key: string;
}

// Créer le hook custom pour obtenir la liste des marques
const useBrandList = () => {
    const [brandList, setBrandList] = useState<BrandTypes[]>([]);

    // Fonction asynchrone pour obtenir la liste des marques
    const getBrandList = async () => {
        try {
            // Faire la requête GET à l'API pour obtenir la liste des marques
            const response = await axios.get('http://15.188.23.24:8642/api/v1/brand');

            // Transformer les données de la réponse en format souhaité
            const brandsSelect: BrandTypes[] = response.data.data.map((brand: any) => ({
                value: brand.name,
                key: brand.name,
            }));

            // Mettre à jour la liste des marques dans le state
            setBrandList(brandsSelect);
        } catch (error) {
            console.error(error);
        }
    };

    // Utiliser useEffect pour appeler getBrandList au montage initial du composant
    useEffect(() => {
        getBrandList();
    }, []);

    // Retourner la liste des marques et la fonction pour la mettre à jour
    return { brandList, setBrandList };
};

export default useBrandList;
