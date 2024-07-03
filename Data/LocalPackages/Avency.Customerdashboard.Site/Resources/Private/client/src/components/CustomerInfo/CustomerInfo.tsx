import React, {useCallback, useEffect, useState} from 'react';
import AdminHeader from "../Utility/AdminHeader.tsx";
import {useParams} from "react-router-dom";
import {SetCustomerAC} from "../../Store/Reducers/customerReducer.ts";
import {useDispatch} from "react-redux";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";
import {SetUsedSystemAC} from "../../Store/Reducers/usedSystemReducer.ts";
import {SetPersonAC} from "../../Store/Reducers/personReducer.ts";

export const CustomerInfo = () => {
    const {customerId} = useParams()
    const usedSystems = useAppSelector<AppRootStateType>(state => state.usedSystem)
    const dispatch = useDispatch();

    const [customer, setCustomer] = useState()
    const [usedSystem, setUsedSystem] = useState()

    const fetchCustomerHandler = useCallback(async () => {
        try {
            if (customerId) {
                const response = await fetch(`https://customer-dashboard.avency.dev/api/customer/${customerId}/usedsystembycustomer`)
                const data = await response.json();

                setCustomer(data)
                dispatch(SetCustomerAC(data));
            }
        } catch (error) {

        }
    }, [dispatch, customerId]);


    useEffect(() => {
        fetchCustomerHandler();
    }, [fetchCustomerHandler])

    console.log(customerId)
    console.log(usedSystems)
    return (
        <div className="w-full">
            <AdminHeader
                headline={"Info über " + customer?.title}
            >
            </AdminHeader>

            <p>Customer Number: {customer?.customerNumber}</p>

        </div>
    );
};
