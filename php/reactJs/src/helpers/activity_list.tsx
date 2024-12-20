export const activityList = [
    { key: "Très Calme", value: "Très Calme" },
    { key: "Calme", value: "Calme" },
    { key: "Normal", value: "Normal" },
    { key: "Agité", value: "Agité" }
];




// // import * as React from 'react';
// import React, { useState, useEffect } from 'react';
// import './Analyse.scss';
// import "./SkillClient.scss"

// //import { useFetch } from "../../hook/useGalerie";
// import data from "../../helpers/competences";
// import axios from 'axios';

// import { FormInput, FormButton } from '../../components/Form/Form';
// import { InputField, SelectField, DateField, DatePlageSelectField, RadioField, RatingField, CheckboxField } from '../../components/Form/Form'


// interface ICatalogProps { }


// const Analyse: React.FunctionComponent<ICatalogProps> = (props) => {

//     const chatSelet = [
//         { "key": "CHAT SANS RACE (EROPE)", "value": "CHAT SANS RACE (EROPE)", },
//         { "key": "ABYSSIN", "value": "ABYSSIN", },
//         { "key": "AMERICAN BOBTAIL", "value": "AMERICAN BOBTAIL", },
//         { "key": "AMERICAN CURL", "value": "AMERICAN CURL", },
//         { "key": "AMERICAN SHORTHAIR", "value": "AMERICAN SHORTHAIR", },
//         { "key": "AMERICAN WIREHAIR", "value": "AMERICAN WIREHAIR", },
//         { "key": "ANATOLI (TURKISH SHORTHAIR)", "value": "ANATOLI (TURKISH SHORTHAIR)", },
//         { "key": "ANGORA TURC", "value": "ANGORA TURC", },
//         { "key": "ASIAN", "value": "ASIAN", },
//         { "key": "AUSTRALIAN MIST", "value": "AUSTRALIAN MIST", },
//         { "key": "BALINAIS", "value": "BALINAIS", },
//         { "key": "BENGAL", "value": "BENGAL", },
//         { "key": "BOMBAY", "value": "BOMBAY", },
//         { "key": "BRAZILIAN SHORTHAIR", "value": "BRAZILIAN SHORTHAIR", },
//         { "key": "BRITISH LONGHAIR", "value": "BRITISH LONGHAIR", },
//         { "key": "BRITISH SHORTHAIR", "value": "BRITISH SHORTHAIR", },
//         { "key": "BURMESE AMERICAIN", "value": "BURMESE AMERICAIN", },
//         { "key": "BURMESE ANGLAIS", "value": "BURMESE ANGLAIS", },
//         { "key": "BURMILLA", "value": "BURMILLA", },
//         { "key": "CALIFORNIAN REX", "value": "CALIFORNIAN REX", },
//         { "key": "CALIFORNIAN SPANGLED", "value": "CALIFORNIAN SPANGLED", },
//         { "key": "CEYLAN", "value": "CEYLAN", },
//         { "key": "CHANTILLY", "value": "CHANTILLY", },
//         { "key": "CHARTREUX", "value": "CHARTREUX", },
//         { "key": "CHAUSIE", "value": "CHAUSIE", },
//         { "key": "COLORPOINT SHORTHAIR", "value": "COLORPOINT SHORTHAIR", },
//         { "key": "CORNISH REX", "value": "CORNISH REX", },
//         { "key": "CYMRIC", "value": "CYMRIC", },
//         { "key": "DEVON REX", "value": "DEVON REX", },
//         { "key": "DONSKOY", "value": "DONSKOY", },
//         { "key": "EUROPEAN SHORTHAIR", "value": "EUROPEAN SHORTHAIR", },
//         { "key": "EXOTIC SHORTHAIR", "value": "EXOTIC SHORTHAIR", },
//         { "key": "GERMAN REX", "value": "GERMAN REX", },
//         { "key": "HAVANA BROWN", "value": "HAVANA BROWN", },
//         { "key": "HIMALAYEN", "value": "HIMALAYEN", },
//         { "key": "JAPANESE BOBTAIL (BOBTAIL JAPONAIS)", "value": "JAPANESE BOBTAIL (BOBTAIL JAPONAIS)", },
//         { "key": "KHAO MANEE", "value": "KHAO MANEE", },
//         { "key": "KORAT", "value": "KORAT", },
//         { "key": "LAPERM", "value": "LAPERM", },
//         { "key": "LYKOI", "value": "LYKOI", },
//         { "key": "MAINE COON", "value": "MAINE COON", },
//         { "key": "MANDARIN", "value": "MANDARIN", },
//         { "key": "MANX", "value": "MANX", },
//         { "key": "MAU ARABE", "value": "MAU ARABE", },
//         { "key": "MAU EGYPTIEN", "value": "MAU EGYPTIEN", },
//         { "key": "MINSKIN", "value": "MINSKIN", },
//         { "key": "MUNCHKIN", "value": "MUNCHKIN", },
//         { "key": "NEBELUNG", "value": "NEBELUNG", },
//         { "key": "NORVEGIEN OU SKOGKATT", "value": "NORVEGIEN OU SKOGKATT", },
//         { "key": "OCICAT", "value": "OCICAT", },
//         { "key": "OJOS AZULES", "value": "OJOS AZULES", },
//         { "key": "ORIENTAL", "value": "ORIENTAL", },
//         { "key": "PERSAN", "value": "PERSAN", },
//         { "key": "PETERBALD", "value": "PETERBALD", },
//         { "key": "PIXIE BOB", "value": "PIXIE BOB", },
//         { "key": "RUSSE (OU BLEU RUSSE)", "value": "RUSSE (OU BLEU RUSSE)", },
//         { "key": "SACRE DE BIRMANIE", "value": "SACRE DE BIRMANIE", },
//         { "key": "SAFARI", "value": "SAFARI", },
//         { "key": "SAVANNAH", "value": "SAVANNAH", },
//         { "key": "SCOTTISH", "value": "SCOTTISH", },
//         { "key": "SELKIRK REX", "value": "SELKIRK REX", },
//         { "key": "SERENGETI", "value": "SERENGETI", },
//         { "key": "SEYCHELLOIS", "value": "SEYCHELLOIS", },
//         { "key": "SIAMOIS", "value": "SIAMOIS", },
//         { "key": "SIBERIEN", "value": "SIBERIEN", },
//         { "key": "SINGAPURA", "value": "SINGAPURA", },
//         { "key": "SKOOKUM", "value": "SKOOKUM", },
//         { "key": "SNOWSHOE", "value": "SNOWSHOE", },
//         { "key": "SOKOKE", "value": "SOKOKE", },
//         { "key": "SOMALI", "value": "SOMALI", },
//         { "key": "SPHYNX", "value": "SPHYNX", },
//         { "key": "THAI", "value": "THAI", },
//         { "key": "TIFFANY", "value": "TIFFANY", },
//         { "key": "TONKINOIS", "value": "TONKINOIS", },
//         { "key": "TOYGER", "value": "TOYGER", },
//         { "key": "TURC DU LAC DE VAN", "value": "TURC DU LAC DE VAN", },
//         { "key": "URAL REX", "value": "URAL REX", },
//         { "key": "YORK CHOCOLAT", "value": "YORK CHOCOLAT" }
//     ]
//     const steriliseSelect = [{ key: "oui", value: true }, { key: "non", value: false }];
//     const ageSelect = [{ key: "De 2 à 4 mois", value: "De 2 à 4 mois" }, { key: "De 4 à 6 mois", value: "De 4 à 6 mois" }, { key: "De 6 à 8 mois", value: "De 6 à 8 mois" }, { key: "De 8 à 12 mois", value: "De 8 à 12 mois" }];
//     const activitySelect = [{ key: "Très Calme", value: "Très Calme" }, { key: "Calme", value: "Calme" }, { key: "Agité", value: "Agité" }];
//     const morphplogieSelect = [{ key: "Maigre", value: "Maigre" }, { key: "Mince", value: "Mince" }, { key: "Normale", value: "Normale" }, { key: "Surpoids", value: "Surpoids" }, { key: "Obèse", value: "Obèse" }];
//     const [brandsSelect, setBrands] = useState([]);
//     const [croqetteSelect, setCroqette] = useState([]);


//     // const [age, setAge] = useState(data[0]);
//     // const [raceChat, setRaceChat] = useState(data[0]);
//     // const [activity, setActivity] = useState(data[0]);
//     // const [morphologie, setMorphologie] = useState(data[0]);
//     // const [brand, setBrand] = useState(data[0]);
//     // const [sterilise, setSterilise] = useState(data[0]);


//     const [response, setResponse] = useState({
//         status: 400,
//         success: false,
//         data: null,
//         message: '',
//     });


//     useEffect(() => {
//         getData();
//     }, [])


//     async function envoyerRequete(data: object) {
//         try {
//             const response = await axios.post('http://localhost:3000/api/v1/croquette/3/analyse', {
//                 "animal": "chat",
//                 "race": "Bengal",
//                 "stade": "De 8 à 12 mois",
//                 "activite": "Très Calme",
//                 "poids": 4.5,
//                 "morphologie": "Mince",
//                 "sterilite": true
//             });
//             setResponse(response.data);
//             console.log(response.data);
//         } catch (error) {
//             console.log(error);
//         }
//     }


//     async function getData() {
//         try {
//             alert('response')
//             const response = await axios.get('http://localhost:3000/api/v1/brand');


//             const brandsSelect: any = [];

//             response.data.data.forEach(function (brands: any) {
//                 brandsSelect.push({
//                     value: brands.name,//parseInt(brands.id),
//                     key: brands.name,
//                 })
//             });

//             setBrands(brandsSelect);
//         } catch (error) {
//             console.error(error);
//         }
//     }


//     const handleSubmit = async (event: any) => {
//         event.preventDefault();
//         // Faire quelque chose avec la valeur sélectionnée
//         const inputs = event.target.querySelectorAll('input');

//         // initialize an empty JSON object
//         const dataForRequest: { [key: string]: string } = {};

//         // initialize an empty JSON object
//         let dataForm: string[] = [];

//         // Itérer sur les inputs et récupérer leur valeur
//         inputs.forEach((input: HTMLInputElement) => {
//             dataForm.push(input.value);
//         });

//         // add a new key-value pair dynamically
//         dataForRequest['animal'] = 'chat';
//         dataForRequest['race'] = dataForm[1];
//         dataForRequest['stade'] = dataForm[2];
//         dataForRequest['activite'] = dataForm[3];
//         dataForRequest['poids'] = '4.5';
//         dataForRequest['sterilite'] = dataForm[5];
//         dataForRequest['morphologie'] = dataForm[4];
//         dataForRequest['marque'] = dataForm[0];

//         try {
//             const response = await axios.post('http://localhost:3000/api/v1/croquette/3/analyse', dataForRequest);
//             setResponse(response.data);
//             console.log(response.data);
//         } catch (error) {
//             console.log(error);
//         }

//     };


//     return (
//         <>
//             <section className="content reverse">
//                 <form className="left" onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '24px', marginTop: '24px', paddingTop: '24px' }}>
//                     <SelectField label={"Marque de croquette"} data={brandsSelect} helperText='Marque de croquette' />
//                     <SelectField label={"La croquette"} data={croqetteSelect} helperText='La croquette' />
//                     <SelectField label={"Race de votre chat"} data={chatSelet} helperText='Bonjour a vous' />
//                     <SelectField label={"Age"} data={ageSelect} helperText='Bonjour a vous' />
//                     <SelectField label={"Niveau d'activité"} data={activitySelect} helperText='Bonjour a vous' />
//                     <SelectField label={"Morphologie"} data={morphplogieSelect} helperText='Bonjour a vous' />
//                     <SelectField label={"Stérilisé"} data={steriliseSelect} helperText='Bonjour a vous' />
//                     <FormButton value={"Analyser"} />
//                 </form>























//                 <section className="right" >
//                         {/* {showCroquette.status === 200 && showCroquette.data !== null ? (

//                             <>
//                                 <p>{JSON.stringify(showCroquette.data, null, 2)}</p>
//                                 <header className="header">
//                                     <img src={showCroquette.data.urlimage} alt="coucou" />
//                                 </header>
//                                 <section className="profil" >
//                                     <HeaderMedium srcImg={user.avatarUri} title={showCroquette.data.name} subtitle={showCroquette.data.brands.name} description={showCroquette.data.categories[0].typePet} />
//                                 </section>
//                                 <DashCounter datasets={{
//                                     proteine: showCroquette.data.characteristic.proteine,
//                                     lipide: showCroquette.data.characteristic.lipide,
//                                     glucide: showCroquette.data.characteristic.glucide,
//                                     fibre: showCroquette.data.characteristic.fibre,
//                                     eau: showCroquette.data.characteristic.eau
//                                 }} />
//                                 {response &&
//                                 }
//                             </>
//                         ) : (
//                             <p>Loading list des croquettes...</p>
//                             )} */}

//                     <pre style={{ backgroundColor: '#000', color: '#fff', padding: '38px' }}>{JSON.stringify(response, null, 2)}</pre>
//                 </section>



//             </section>

//         </>
//     );
// };

// export default Analyse;






// {/* Table */}
// <TableContainer component={Paper}>
// <Table>
//     <TableHead>
//         <TableRow>
//             <TableCell>Marque</TableCell>
//             <TableCell>Croquette</TableCell>
//             <TableCell>Age</TableCell>
//             <TableCell>Sterilise</TableCell>
//             <TableCell>Race</TableCell>
//             <TableCell>Activité</TableCell>
//             <TableCell>Morphologie</TableCell>
//         </TableRow>
//     </TableHead>
//     <TableBody>
//         {tableData.map((row, index) => (
//             <TableRow key={index}>
//                 <TableCell>{row.marque}</TableCell>
//                 <TableCell>{row.croquette}</TableCell>
//                 <TableCell>{row.stade}</TableCell>
//                 <TableCell>{row.sterilite}</TableCell>
//                 <TableCell>{row.race}</TableCell>
//                 <TableCell>{row.activite}</TableCell>
//                 <TableCell>{row.morphologie}</TableCell>
//             </TableRow>
//         ))}
//     </TableBody>
// </Table>
// </TableContainer>
// {/* <pre style={{ backgroundColor: '#000', color: '#fff', padding: '38px' }}>{JSON.stringify(response, null, 2)}</pre> */}






// {
//     labels: 2023,
//     datasets: [{
//       label: "User gain",
//       data: [12, 19, 3,],
//       fill: true, // permet de faire apparaitre le backgroundColor 
//       //backgroundColor: '#0090071f',
//       backgroundColor: [
//         '#009007cf',
//         '#000',
//         '#aaa',
//         '#ff9800ba'
//       ],
//       borderColor: '#009007',
//       pointBorderColor: '#000',
//       pointBorderWidth: 0,
//       //tension: 1,
//       borderRadius: 100,
//       spacing: 14,
//       cutout: '88%',
//       hoverBackgroundColor: '#000',
//       hoverBorderColor: '#000',
//       borderWidth: 0,

//     }]
//   }