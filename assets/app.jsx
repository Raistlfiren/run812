import React from 'react';
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Base from './js/components/layout/Base';
import './styles/app.scss';
import {render} from "react-dom";

const rootElement = document.getElementById("root");
render(
    <BrowserRouter>
        <Routes>
            <Route path="/" element={<Base />} />
            <Route path="/routes" />
            <Route path="/codes" />
        </Routes>
    </BrowserRouter>
    , rootElement);
