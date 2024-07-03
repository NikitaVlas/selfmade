import React, {useCallback, useEffect, useState} from 'react';
import CustomerFormTitle from "../CustomerForm/CustomerFormTitle.tsx";
import Input from "../Utility/FormElements/Input.tsx";
import AdminHeader from "../Utility/AdminHeader.tsx";
import Button from "../Utility/Button.tsx";
import {NavLink} from "react-router-dom";
import LineForm from "../Utility/LineForm.tsx";
import {useDispatch} from "react-redux";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";
import {AddUsedSystemAC} from "../../Store/Reducers/usedSystemReducer.ts";
import Select from "../Utility/FormElements/Select.tsx";
import {SetCustomerAC} from "../../Store/Reducers/customerReducer.ts";
import {SetPersonAC} from "../../Store/Reducers/personReducer.ts";

function SystemForm() {
    const dispatch = useDispatch();
    const customers = useAppSelector<AppRootStateType>(state => state.customer)
    const persons = useAppSelector<AppRootStateType>(state => state.person)

    const [title, setTitle] = useState("")
    const [usedVersion, setUsedVersion] = useState("")
    const [cookie, setCookie] = useState("")
    const [trackingsTools, setTrackingsTools] = useState("")
    const [sslCertificate, setSslCertificate] = useState("")
    const [urlLocal, setUrlLocal] = useState( "")
    const [urlPreview, setUrlPreview] = useState( "")
    const [urlLive, setUrlLive] = useState( "")
    const [customer, setCustomer] = useState("")
    const [productManager, setProductManager] = useState("")
    const [leadDev, setLeadDev] = useState("")

    const fetchCustomersHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customers`);
            const data = await response.json();
            dispatch(SetCustomerAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    const fetchPersonsHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customer/getPersons`);
            const data = await response.json();

            dispatch(SetPersonAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    const addSystemHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/addNewUsedSystem`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    usedVersion: usedVersion,
                    cookie: cookie,
                    trackingsTools: trackingsTools,
                    sslCertificate: sslCertificate,
                    urlLocal: urlLocal,
                    urlPreview: urlPreview,
                    urlLive: urlLive,
                    customer: customer,
                    productManager: productManager,
                    leadDev: leadDev
                })
            });

            if (response.ok) {
                const updatedData = await response.json();
                dispatch(AddUsedSystemAC(updatedData));
            } else {
                console.log("Save error");
            }
        } catch (error) {
            console.error(error);
        }
    }, [dispatch, title, usedVersion, cookie, trackingsTools, sslCertificate, urlLocal, urlPreview, urlLive, customer, productManager, leadDev]);

    const onChangTitleHandler = (value: any) => {
        setTitle(value)
    }

    const onChangTrackingToolsHandler = (value: any) => {
        setTrackingsTools(value)
    }

    const onChangVersionHandler = (value: any) => {
        setUsedVersion(value)
    }

    const onChangSslCertificateHandler = (value: any) => {
        setSslCertificate(value)
    }

    const onChangeHandlerCustomerTitle = (value: any) => {
        setCustomer(value)
    }

    const onChangeHandlerProductManager = (value: any) => {
        setProductManager(value)
    }

    const onChangeHandlerLeadDev = (value: any) => {
        setLeadDev(value)
    }

    const onChangeHandlerCookie = (value: any) => {
        setCookie(value)
    }

    const onChangeHandlerUrlLocal = (value: any) => {
        setUrlLocal(value)
    }

    const onChangeHandlerUrlPreview = (value: any) => {
        setUrlPreview(value)
    }

    const onChangeHandlerUrlLive = (value: any) => {
        setUrlLive(value)
    }

    useEffect(() => {
        fetchCustomersHandler();
        fetchPersonsHandler();
    }, [fetchCustomersHandler, fetchPersonsHandler])

    return (
        <div className="w-full">
            <AdminHeader
                headline={"Verwendete Systeme"}
            >
            </AdminHeader>
            <div className="flex justify-between mt-6 mb-6 ml-12 mr-12">
                <Button variant="secondaryOutlined">
                    <NavLink to={'/systems'}
                             className=''>
                        {"Abbrechen"}
                    </NavLink>
                </Button>
                <Button onClick={addSystemHandler}>{"Speichern"}</Button>
            </div>
            <LineForm classList="mx-auto w-[93%] h-[2px] bg-gray-100"></LineForm>
            <div className="mt-6 mt-6 mb-6 ml-12 mr-12">
                <CustomerFormTitle
                    classList="mb-3"
                    title={"Trage hier alle Informationen zum Projekt ein."}>
                </CustomerFormTitle>
                <div className="flex">
                    <div className="mr-6">
                        <Select title={"Kunde auswählen"} classList={"mr-5 mb-3"} onChange={onChangeHandlerCustomerTitle}>
                            <option>{"Kunde auswählen"}</option>
                            {
                                customers.map(customer => {
                                    return (
                                        <option key={customer.identifier}
                                                value={customer.identifier}>{customer.title}</option>
                                    )
                                })
                            }
                        </Select>
                        <Input
                            title={"Eingesetztes System"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"System"}
                            value={title}
                            onChange={onChangTitleHandler}
                        />
                        <Input
                            title={"Version"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"Version"}
                            value={usedVersion}
                            onChange={onChangVersionHandler}
                        />
                    </div>
                    <div className="mr-6">
                        <Input
                            title={"Cookie"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"Cookie"}
                            value={cookie}
                            onChange={onChangeHandlerCookie}
                        />
                        <Input
                            title={"Trackingtool"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"Trackingtool"}
                            value={trackingsTools}
                            onChange={onChangTrackingToolsHandler}
                        />
                        <Input
                            title={"SSL-Zertifikat"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"SSL-Zertifikat"}
                            value={sslCertificate}
                            onChange={onChangSslCertificateHandler}
                        />
                    </div>
                    <div className="mr-6">
                        <Select title={"Product manager auswählen"} classList={"mr-5 mb-3"} onChange={onChangeHandlerProductManager}>
                            <option>{"Product manager auswählen"}</option>
                            {
                                persons.map(person => {
                                    return (
                                        <option key={person.identifier} value={person.identifier}>{person.firstName} {person.lastName}</option>
                                    )
                                })
                            }
                        </Select>
                        <Select title={"Lead DEV auswählen"} classList={"mr-5 mb-3"} onChange={onChangeHandlerLeadDev}>
                            <option>{"Lead DEV auswählen"}</option>
                            {
                                persons.map(person => {
                                    return (
                                        <option key={person.identifier} value={person.identifier}>{person.firstName} {person.lastName}</option>
                                    )
                                })
                            }
                        </Select>
                    </div>
                </div>
                <CustomerFormTitle
                    classList="mb-3"
                    title={"Trage hier url Adressen."}>
                </CustomerFormTitle>
                <div className="w-80">
                    <Input
                        title={"Url Local"}
                        inputType="text"
                        classList="mb-3"
                        placeholder={"Url Local"}
                        value={urlLocal}
                        onChange={onChangeHandlerUrlLocal}
                    />
                    <Input
                        title={"Url Preview"}
                        inputType="text"
                        classList="mb-3"
                        placeholder={"Url Preview"}
                        value={urlPreview}
                        onChange={onChangeHandlerUrlPreview}
                    />
                    <Input
                        title={"Url Live"}
                        inputType="text"
                        classList="mb-3"
                        placeholder={"Url Live"}
                        value={urlLive}
                        onChange={onChangeHandlerUrlLive}
                    />
                </div>
            </div>
        </div>
    );
}

export default SystemForm;
