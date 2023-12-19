import React, { useState, useEffect, ChangeEventHandler } from 'react';
import './Analyse.scss';
import "./SkillClient.scss";
import { Button } from '@mui/material/';
import Alert from '@mui/material/Alert';
import AlertTitle from '@mui/material/AlertTitle';
import { SelectChangeEvent } from '@mui/material/';

import { Bar } from '../../components/Opinion/Opinion';
import { Header } from '../../components/Header/Header';

import useAnalyse, { AnalyseDataResponseTypes } from '../../api/useAnalyse';
import useFormValues from '../../hook/useFormValues';
import useStepTracker from '../../hook/useStepTracker';

import FieldCroquetteComponent from "../../components/Form/FieldCroquetteComponent";
import FieldRaceComponent from "../../components/Form/fieldRaceComponent";
import FieldCaractaireComponent from "../../components/Form/FieldCaractaireComponent";

import lang_fr from '../../lang/fr';



const Form: React.FC = () => {

    // Définition des states pour les valeurs du formulaire et les données de la table
    const formAdmin = useFormValues();
    // Utilisation du hook pour suivre les étapes
    const trackerStep = useStepTracker();
    const { response, getAnalyse } = useAnalyse();

    // Gestionnaire de soumission du formulaire
    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        const { formData } = formAdmin;
        event.preventDefault();
        getAnalyse(formData);
    };

    const handleButtonBuyClick = () => {
        if ('data' in response && 'url' in response.data && response.data.url !== '') {
            window.location.href = response.data.url;
        }
    };

    return (

        <section className="content reverse">
            <form className="left" onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '24px', marginTop: '24px', paddingTop: '24px' }}>

                {/* Champ select pour la ligne de croquette */}
                <FieldCroquetteComponent
                    trackerStep={trackerStep}
                    formAdmin={formAdmin}
                />

                {/* Champ select pour la race */}
                <FieldRaceComponent
                    trackerStep={trackerStep}
                    formAdmin={formAdmin}
                />

                {/* Champ select pour les caractere */}
                <FieldCaractaireComponent
                    trackerStep={trackerStep}
                    formAdmin={formAdmin}
                />

            </form>

            <section className="right" >
                {(Object.keys(response).length !== 0) && ((response as AnalyseDataResponseTypes).status === 200 && (response as AnalyseDataResponseTypes).data !== null) ? (
                    <>
                        <section className="profil" >
                            <div style={{ flex: "2" }}>
                                <Header srcImg={(response as AnalyseDataResponseTypes).data.urlimage} title={(response as AnalyseDataResponseTypes).data.name} subtitle={(response as AnalyseDataResponseTypes).data.marque} description={(response as AnalyseDataResponseTypes).data.analyse_quantitatif_nutriment.proteine ? 'Croquettes pour chat stérilisé' : 'Croquettes pour chat non-stérilisé'} styleImage='avatarSqareMax' styleContent='headerCmpCenter' styleTitle='headerTitle' />
                                {('data' in response && 'url' in response.data && response.data.url !== '') ? <Button type="submit" variant="contained" color="primary" className='button-bay' onClick={handleButtonBuyClick}>
                                    {lang_fr.acheter_croquette /* Acheter une croquette */}
                                </Button> : ''}
                            </div>
                            <div className="noteBox" style={{ flex: "2" }}>
                            </div>
                        </section>


                        <section className="profil" >
                            <div className="noteBox">
                                <div className="title title_margin titleCard">
                                    {lang_fr.apport_nutritif_croquettes /* Les nutriments dont votre chat a besoin */}
                                </div>
                                <Bar title="Proteine" value={(response as AnalyseDataResponseTypes).data.element_nutritif.proteine / 10} status={(response as AnalyseDataResponseTypes).data.analyse_quantitatif_nutriment.proteine} />
                                <Bar title="Lipide" value={(response as AnalyseDataResponseTypes).data.element_nutritif.lipide / 10} status={(response as AnalyseDataResponseTypes).data.analyse_quantitatif_nutriment.lipide} />
                                <Bar title="Glucide" value={(response as AnalyseDataResponseTypes).data.element_nutritif.ENA / 10} status={(response as AnalyseDataResponseTypes).data.analyse_quantitatif_nutriment.ENA} />
                                <Bar title="Fibre" value={(response as AnalyseDataResponseTypes).data.element_nutritif.fibre / 10} status={true} />
                                <Bar title="Eau" value={(response as AnalyseDataResponseTypes).data.element_nutritif.eau / 10} status={true} />
                            </div>
                            <div className="noteBox">
                                <div className="title title_margin titleCard">
                                    {lang_fr.calories_chat_besion /* Les calories dont votre chat a besion */}
                                </div>
                                <text className='scrore'> {(response as AnalyseDataResponseTypes).data.energie_metabolisable} / {Math.round((response as AnalyseDataResponseTypes).data.besoin_energetique)} </text><text>*</text>

                                <Alert severity="warning">
                                    <AlertTitle>{lang_fr.a_savoir /* A savoir */}</AlertTitle>
                                    {(response as AnalyseDataResponseTypes).data.commentaire}
                                </Alert>
                            </div>
                        </section>
                        <section className="profil" >
                            <p className='description'>
                                * {lang_fr.explication_besion /* Le besoin énergétique de votre chat est de  */}
                                <text className='red txt-bold'> {Math.round((response as AnalyseDataResponseTypes).data.besoin_energetique)} </text>
                                <text className='subTitle'>kcal/g</text>, {lang_fr.explication_apport /* tandis que l'apport énergétique des croquettes est de   */}
                                <text className='green txt-bold'> {(response as AnalyseDataResponseTypes).data.energie_metabolisable}  </text><text className='subTitle'>kcal/g</text>.</p>
                        </section>
                    </>
                ) : (
                    <div className='waiter'>
                        <img src={require("../../images/avatar/chat.png")} className="waiter-img" alt="chat" />
                        <p>{lang_fr.Attente_analyse /* En attente d'analyse .. */}.</p>
                    </div>
                )}
            </section>
        </section>
    );
};

export default Form;