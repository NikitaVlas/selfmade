import React, {useCallback, useEffect, useState} from 'react';
import AdminHeader from "../Utility/AdminHeader.tsx";
import Button from "../Utility/Button.tsx";
import {NavLink, useParams} from "react-router-dom";
import Input from "../Utility/FormElements/Input.tsx";
import CustomerFormTitle from "./CustomerFormTitle.tsx";
import LineForm from "../Utility/LineForm.tsx";
import {useDispatch} from "react-redux";
import {SetCustomerAC} from "../../Store/Reducers/customerReducer.ts";
import Select from "../Utility/FormElements/Select.tsx";
import {SetPersonAC} from "../../Store/Reducers/personReducer.ts";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";

function CustomerForm() {
    const [customer, setCustomer] = useState()
    const [priority, setPriority] = useState(customer ? customer.priority : 0)
    const [productManagerDefault, setProductManagerDefault] = useState(customer ? customer.productManagerDefault : "")

    const {customerId} = useParams()
    const persons = useAppSelector<AppRootStateType>(state => state.person)
    const dispatch = useDispatch();

    const fetchCustomerHandler = useCallback(async () => {
        try {
            if (customerId) {
                const response = await fetch(`https://customer-dashboard.avency.dev/api/customer/${customerId}`)
                const data = await response.json();

                setCustomer(data)
                dispatch(SetCustomerAC(data));
            }
        } catch (error) {

        }
    }, [dispatch, customerId]);

    const fetchPersonsHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customer/getPersons`);
            const data = await response.json();

            dispatch(SetPersonAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    const updateHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customer/${customerId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    priority: priority,
                    productManagerDefault: typeof productManagerDefault === 'string' ? productManagerDefault : productManagerDefault?.identifier,
                })
            });

            if (response.ok) {
                const updatedData = await response.json();
                const updatedCustomer = {...customer, priority: updatedData.priority, productManagerDefault: updatedData.productManagerDefault};
                setCustomer(updatedCustomer);
                dispatch(SetCustomerAC(updatedCustomer));
            } else {
                console.log("Save error");
            }
        } catch (error) {
            console.error(error);
        }
    }, [dispatch, customer, priority, productManagerDefault, customerId]);

    const onChangeHandlerPriority = (value: any) => {
        setPriority(value);
    }

    const onChangeHandlerProductManagerDefault = (value: any) => {
        setProductManagerDefault(value);
    }

    useEffect(() => {
        fetchCustomerHandler();
        fetchPersonsHandler();
    }, [fetchCustomerHandler, fetchPersonsHandler])

    useEffect(() => {
        if (customer && customer.priority) {
            setPriority(customer.priority);
        }
        if (customer && customer.productManagerDefault) {
            setProductManagerDefault(customer.productManagerDefault);
        }
    }, [customer]);

    return (
        <div className="w-full ">
            <AdminHeader
                headline={"Customer form"}
            >
            </AdminHeader>
            <div className="flex justify-between mt-6 mb-6 ml-12 mr-12">
                <Button variant="secondaryOutlined">
                    <NavLink to={'/customerList'}
                             className=''>
                        {"Abbrechen"}
                    </NavLink>
                </Button>
                <Button onClick={updateHandler}>{"Speichern"}</Button>
            </div>
            <LineForm classList="mx-auto w-[93%] h-[2px] bg-gray-100"></LineForm>
            <div className="flex justify-between mt-6 ml-12 mr-12">
                <div>
                    <CustomerFormTitle
                        classList="mb-3"
                        title={"Trage hier alle Informationen vom Kunden ein."}>
                    </CustomerFormTitle>
                    <div className="flex">
                        <div className="mr-6">
                            <Input
                                title={"Name des Kunden*"}
                                inputType="text"
                                classList="mb-3"
                                value={customer?.title}
                            />
                            <Input
                                title={"Kundennummer*"}
                                inputType="text"
                                classList="mb-3"
                                value={customer?.customerNumber}
                            />
                        </div>
                        <div>
                            <Input
                                title={"Kundenkürzel*"}
                                inputType="text"
                                classList="mb-3"
                                value={customer?.abbreviation}
                            />
                            <Input
                                title={"Priority"}
                                inputType="text"
                                classList="mb-3"
                                placeholder={"Priority"}
                                value={priority}
                                onChange={onChangeHandlerPriority}
                            />
                        </div>
                    </div>
                </div>
                <LineForm classList="w-[2px] h-100% bg-gray-100"></LineForm>
                <div>
                    <CustomerFormTitle
                        classList="mb-3"
                        title={"Trage hier alle Informationen zum Ansprechpartner ein."}>
                    </CustomerFormTitle>
                    <div className="flex">
                        <div className="mr-6">
                            <Input
                                title={"Vorname*"}
                                inputType="text"
                                classList="mb-3"
                                value={customer?.contactPerson?.firstname}
                            />
                            <Input
                                title={"E-mail*"}
                                inputType="email"
                                classList="mb-3"
                                placeholder={customer?.contactPerson.email}
                            />
                        </div>
                        <div>
                            <Input
                                title={"Nachname*"}
                                inputType="text"
                                classList="mb-3"
                                value={customer?.contactPerson?.lastname}
                            />
                            <Input
                                title={"Telefonnummer*"}
                                inputType="tel"
                                classList="mb-3"
                                placeholder={customer?.contactPerson?.phone}
                            />
                        </div>
                    </div>
                </div>
            </div>
            <LineForm classList="mx-auto w-[93%] h-[2px] bg-gray-100 mt-6"></LineForm>
            <div className="mt-6 ml-12 mr-12">
                <CustomerFormTitle
                    classList="mb-3"
                    title={"Trage hier alle Informationen von avency ein."}>
                </CustomerFormTitle>
                <div className="flex">
                    <div className="flex flex-col gap-1 model-container mb-[50px] mr-6">
                        <Select classList={"mr-5"} onChange={onChangeHandlerProductManagerDefault}>
                            <option>{"Auswählen"}</option>
                            {
                                persons.map(person => {
                                    if (customer?.productManagerDefault?.identifier == person.identifier) {
                                        return (
                                            <option key={person.identifier} selected value={person.identifier}>{person.firstName}</option>
                                        )
                                    }
                                    return (
                                        <option key={person.identifier} value={person.identifier}>{person.firstName}</option>
                                    )
                                })
                            }
                        </Select>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default CustomerForm;
