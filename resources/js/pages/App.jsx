import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter as Router } from "react-router-dom";
import DarkThemeProvider from "./context/darkTheme";

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
