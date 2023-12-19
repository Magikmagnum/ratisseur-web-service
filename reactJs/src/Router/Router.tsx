import React from 'react';
import { Routes, Route } from 'react-router-dom';

import Analyse from '../pages/Analyse/Analyse';

const Router = () => {


    return (
        <Routes>
            <Route path='/' element={<Analyse />} />
        </Routes>
    );
};

export default Router;


