import {BrowserRouter, Route, Routes} from 'react-router-dom';
import CustomerList from "./components/CustomerList/CustomerList.tsx";
import TroiProjects from "./components/TroiProjects/TroiProjects.tsx";
import Layout from "./components/Layout/Layout.tsx";
import CustomerForm from "./components/CustomerForm/CustomerForm.tsx";
import Systems from "./components/Systems/Systems.tsx";
import SystemForm from "./components/SystemForm/SystemForm.tsx";
import System from "./components/Systems/System.tsx";
import SystemByCustomer from "./components/Systems/SystemByCustomer.tsx";
import {CustomerInfo} from "./components/CustomerInfo/CustomerInfo.tsx";

function App() {
    return (
        <BrowserRouter>
            <div className="">
                <Layout className=''>
                    <Routes>
                        <Route
                            path="/customerList"
                            element={<CustomerList/>}
                        />
                        <Route
                            path="/troi"
                            element={<TroiProjects/>}
                        />
                        <Route
                            path="/customerForm/:customerId"
                            element={<CustomerForm/>}
                        />
                        <Route
                            path="/systems"
                            element={<Systems/>}
                        />
                        <Route
                            path="/usedsystem/:usedSystemId"
                            element={<System/>}
                        />
                        <Route
                            path="/systemForm"
                            element={<SystemForm/>}
                        />
                        <Route
                            path="/usedSystemByCustomer/:customerId"
                            element={<SystemByCustomer/>}
                        />
                        <Route
                            path="/customerInfo/:customerId"
                            element={<CustomerInfo/>}
                        />
                    </Routes>
                </Layout>
            </div>
        </BrowserRouter>
    )
}

export default App
