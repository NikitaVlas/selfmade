import React, {useCallback, useEffect, useState} from 'react';
import {NavLink, useParams} from "react-router-dom";
import {SetCustomerAC} from "../../Store/Reducers/customerReducer.ts";
import {useDispatch} from "react-redux";
import AdminHeader from "../Utility/AdminHeader.tsx";
import Button from "../Utility/Button.tsx";
import LineForm from "../Utility/LineForm.tsx";
import CustomerFormTitle from "../CustomerForm/CustomerFormTitle.tsx";
import Input from "../Utility/FormElements/Input.tsx";
import Select from "../Utility/FormElements/Select.tsx";
import {AddUsedSystemAC} from "../../Store/Reducers/usedSystemReducer.ts";
import {SetPersonAC} from "../../Store/Reducers/personReducer.ts";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";

const SystemByCustomer = () => {
    const {customerId} = useParams()

    const persons = useAppSelector<AppRootStateType>(state => state.person)

    const [customer, setCustomer] = useState()
    const [title, setTitle] = useState("")
    const [usedVersion, setUsedVersion] = useState("")
    const [cookie, setCookie] = useState("")
    const [trackingsTools, setTrackingsTools] = useState("")
    const [sslCertificate, setSslCertificate] = useState("")
    const [urlLocal, setUrlLocal] = useState( "")
    const [urlPreview, setUrlPreview] = useState( "")
    const [urlLive, setUrlLive] = useState( "")
    const [productManager, setProductManager] = useState("")
    const [leadDev, setLeadDev] = useState("")


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
                    customer: typeof customer === 'string' ? customer : customer?.identifier,
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
    }, [dispatch, title, usedVersion, cookie, trackingsTools, sslCertificate, urlLocal, urlPreview, urlLive, customer,productManager, leadDev]);

    const onChangTitleHandler = (value: any) => {
        setTitle(value)
    }

    const onChangTrackingToolsHandler = (value: any) => {
        setTrackingsTools(value)
    }

    const onChangeUsedVersionHandler = (value: any) => {
        setUsedVersion(value)
    }

    const onChangSslCertificateHandler = (value: any) => {
        setSslCertificate(value)
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

    const onChangeUrlLocalHandler = (value: any) => {
        setUrlLocal(value)
    }

    const onChangeHandlerUrlPreview = (value: any) => {
        setUrlPreview(value)
    }

    const onChangeUrlLiveHandler = (value: any) => {
        setUrlLive(value)
    }

    useEffect(() => {
        fetchCustomerHandler();
        fetchPersonsHandler();
    }, [fetchCustomerHandler, fetchPersonsHandler])

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
                        <Input
                            title={"Name des Kunden"}
                            inputType="text"
                            classList="mb-3"
                            value={customer?.title}
                        />
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
                            onChange={onChangeUsedVersionHandler}
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
                            <option value={""}>{"Product manager auswählen"}</option>
                            {
                                persons.map(person => {
                                    // if (usedSystem?.productManager?.identifier == person.identifier) {
                                    //     return (
                                    //         <option key={person.identifier} selected value={person.identifier}>{person.firstName}{" "}{person.lastName}</option>
                                    //     )
                                    // }
                                    return (
                                        <option key={person.identifier} value={person.identifier}>{person.firstName}{" "}{person.lastName}</option>
                                    )
                                })
                            }
                        </Select>
                        <Select title={"Lead DEV auswählen"} classList={"mr-5 mb-3"} onChange={onChangeHandlerLeadDev}>
                            <option value={""}>{"Lead DEV auswählen"}</option>
                            {
                                persons.map(person => {
                                    // if (usedSystem?.leadDev?.identifier == person.identifier) {
                                    //     return (
                                    //         <option key={person.identifier} selected value={person.identifier}>{person.firstName}{" "}{person.lastName}</option>
                                    //     )
                                    // }
                                    return (
                                        <option key={person.identifier} value={person.identifier}>{person.firstName}{" "}{person.lastName}</option>
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
                        onChange={onChangeUrlLocalHandler}
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
                        onChange={onChangeUrlLiveHandler}
                    />
                </div>
            </div>
        </div>
    );
};

export default SystemByCustomer;
