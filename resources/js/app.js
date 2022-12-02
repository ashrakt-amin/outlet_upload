// resources/js/app.js
import './bootstrap';

import './index.css'

import React from "react";
import ReactDOM from "react-dom/client";
import Main from './Pages/Main';


const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
<Main />
);
