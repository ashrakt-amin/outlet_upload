import React from "react";
import { BrowserRouter } from "react-router-dom";
import DarkThemeProvider from "./context/darkTheme";

import { Provider } from "react-redux";
import { store } from "./Redux/store";

import Basic from "./Basic";

const Main = () => {
    return (
        <div>
            <DarkThemeProvider>
                <Provider store={store}>
                    <BrowserRouter>
                        <Basic />
                    </BrowserRouter>
                </Provider>
            </DarkThemeProvider>
        </div>
    );
};

export default Main;
