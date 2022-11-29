import React from "react";
import ReactDOM from "react-dom/client";
import reportWebVitals from "./reportWebVitals";
import { BrowserRouter as Router } from "react-router-dom";
import DarkThemeProvider from "./context/darkTheme";

import "./index.css";

import { Provider } from "react-redux";
import { store } from "./Redux/store";
import Basic from "./Basic";

const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
    <Router>
        <DarkThemeProvider>
            <Provider store={store}>
                <Basic />
            </Provider>
        </DarkThemeProvider>
    </Router>
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
