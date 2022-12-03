import { lazy, Suspense } from "react";
import { Route, Routes } from "react-router-dom";
import HomePage from "./pages/Home/HomePage";

import TraderRegister from "./pages/TraderRegister/TraderRegister";

import { disableReactDevTools } from "@fvilers/disable-react-devtools";
import TraderProducts from "./pages/TraderDashboard/TraderProducts/TraderProducts";
import TraderOrders from "./pages/TraderDashboard/TraderOrders/TraderOrders";
import TraderProductDetails from "./pages/TraderDashboard/TraderProductDetails/TraderProductDetails";
import TraderLogin from "./pages/TraderLogin/TraderLogin";
import TraderOwnInfo from "./pages/TraderDashboard/TraderComponents/TraderOwnInfo/TraderOwnInfo";
import TraderAddingProducts from "./pages/TraderDashboard/TraderAddingProducts/TraderAddingProducts";

function Basic() {
    if (process.env.NODE_ENV === "production") {
        disableReactDevTools();
    }

    const Dashboard = lazy(() => import("./pages/Dashboard"));
    const AdminLogin = lazy(() =>
        import("./pages/AdminPages/AdminLogin/AdminLogin")
    );
    const TraderDashboard = lazy(() =>
        import("./pages/TraderDashboard/TraderDashboard")
    );

    return (
        <Suspense fallback={<h1>Loading ......</h1>}>
            <Routes>
                <Route path="/*" element={<HomePage />} />

                <Route path="/dachboard/*" element={<Dashboard />} />

                <Route path="/trader/dachboard/" element={<TraderDashboard />}>
                    <Route
                        path="/trader/dachboard/"
                        element={<TraderProducts />}
                    />
                    <Route
                        path="/trader/dachboard/traderProduct/:id"
                        element={<TraderProductDetails />}
                    />
                    <Route
                        path="/trader/dachboard/myorders"
                        element={<TraderOrders />}
                    />
                    <Route
                        path="/trader/dachboard/traderinfo"
                        element={<TraderOwnInfo />}
                    />
                    <Route
                        path="/trader/dachboard/addproducts"
                        element={<TraderAddingProducts />}
                    />
                </Route>

                <Route path="/adminlogin" element={<AdminLogin />} />

                <Route path="/traderRegister" element={<TraderRegister />} />
                <Route path="/traderLogin" element={<TraderLogin />} />
            </Routes>
        </Suspense>
    );
}

export default Basic;
