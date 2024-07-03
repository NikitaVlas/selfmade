import React, {useCallback, useEffect, useState} from 'react';
import AdminHeader from "../Utility/AdminHeader.tsx";
import Button from "../Utility/Button.tsx";
import {NavLink, useParams} from "react-router-dom";
import LineForm from "../Utility/LineForm.tsx";
import CustomerFormTitle from "../CustomerForm/CustomerFormTitle.tsx";
import Select from "../Utility/FormElements/Select.tsx";
import Input from "../Utility/FormElements/Input.tsx";
import {useDispatch} from "react-redux";
import {SetUsedSystemAC, UpdateUsedSystemAC} from "../../Store/Reducers/usedSystemReducer.ts";
import {SetPersonAC} from "../../Store/Reducers/personReducer.ts";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";
import {SetCustomerAC} from "../../Store/Reducers/customerReducer.ts";

const System = () => {
    const {usedSystemId} = useParams()
    const persons = useAppSelector<AppRootStateType>(state => state.person)
    const dispatch = useDispatch();

    const [usedSystem, setUsedSystem] = useState(null)
    const { title, usedVersion, cookie, trackingsTools, sslCertificate, urlLocal, urlPreview, urlLive, productManager, leadDev } = usedSystem || {};

    const fetchUsedSystemHandler = useCallback(async () => {
        try {
            if (usedSystemId) {
                const response = await fetch(`https://customer-dashboard.avency.dev/api/usedsystem/${usedSystemId}`)
                const data = await response.json();

                setUsedSystem(data)
                dispatch(SetUsedSystemAC(data));
            }
        } catch (error) {

        }
    }, [dispatch, usedSystemId]);

    const fetchPersonsHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customer/getPersons`);
            const data = await response.json();

            dispatch(SetPersonAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    const fetchCustomersHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/customers`);
            const data = await response.json();
            dispatch(SetCustomerAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    const updateHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/usedsystem/${usedSystemId}/update`, {
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
                    productManager: typeof productManager === 'string' ? productManager : productManager?.identifier,
                    leadDev: typeof leadDev === 'string' ? leadDev : leadDev?.identifier,

                })
            });

            if (response.ok) {
                const updatedData = await response.json();
                const updatedUsedSystem = {...usedSystem,
                    title: updatedData.title,
                    usedVersion: updatedData.usedVersion,
                    cookie: updatedData.cookie,
                    trackingsTools: updatedData.trackingsTools,
                    sslCertificate: updatedData.sslCertificate,
                    urlLocal: updatedData.urlLocal,
                    urlPreview: updatedData.urlPreview,
                    urlLive: updatedData.urlLive,
                    productManager: updatedData.productManager,
                    leadDev: updatedData.leadDev,
                };
                setUsedSystem(updatedUsedSystem);
                dispatch(UpdateUsedSystemAC(updatedUsedSystem));
            } else {
                console.log("Save error");
            }
        } catch (error) {
            console.error(error);
        }
    }, [dispatch, usedSystem, title, usedVersion, cookie, trackingsTools, sslCertificate, urlLocal, urlPreview, urlLive, productManager, leadDev, usedSystemId]);

    useEffect(() => {
        fetchUsedSystemHandler();
        fetchPersonsHandler();
        fetchCustomersHandler;
    }, [fetchUsedSystemHandler, fetchPersonsHandler, fetchCustomersHandler])

    const onChangeTitleHandler = (value: any) => {
        setUsedSystem({...usedSystem, title: value});
    }

    const onChangeUsedVersionHandler = (value: any) => {
        setUsedSystem({...usedSystem, usedVersion: value});
    }

    const onChangeTrackingsToolsHandler = (value: any) => {
        setUsedSystem({...usedSystem, trackingsTools: value});
    }

    const onChangeSslCertificateHandler = (value: any) => {
        setUsedSystem({...usedSystem, sslCertificate: value});
    }

    const onChangeHandlerProductManager = (value: any) => {
        setUsedSystem({...usedSystem, productManager: value});
    }

    const onChangeHandlerLeadDev = (value: any) => {
        setUsedSystem({...usedSystem, leadDev: value});
    }

    const onChangeCookieHandler = (value:any) => {
        setUsedSystem({...usedSystem, cookie: value});
    }

    const onChangeUrlLocalHandler = (value: any) => {
        setUsedSystem({...usedSystem, urlLocal: value});
    }

    const onChangeUrlPreviewHandler = (value: any) => {
        setUsedSystem({...usedSystem, urlPreview: value});
    }

    const onChangeUrlLiveHandler = (value: any) => {
        setUsedSystem({...usedSystem, urlLive: value});
    }

    useEffect(() => {
        if (usedSystem) {
            setUsedSystem({
                ...usedSystem,
                title: usedSystem.title || title,
                usedVersion: usedSystem.usedVersion || usedVersion,
                cookie: usedSystem.cookie || cookie,
                trackingsTools: usedSystem.trackingsTools || trackingsTools,
                sslCertificate: usedSystem.sslCertificate || sslCertificate,
                urlLocal: usedSystem.urlLocal || urlLocal,
                urlPreview: usedSystem.urlPreview || urlPreview,
                urlLive: usedSystem.urlLive || urlLive,
                productManager: usedSystem.productManager || productManager,
                leadDev: usedSystem.leadDev || leadDev,
            });
        }
    }, [usedSystem, title, usedVersion, cookie, trackingsTools, sslCertificate, urlLocal, urlPreview, urlLive, productManager, leadDev])

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
                <Button onClick={updateHandler}>{"Speichern"}</Button>
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
                            value={usedSystem?.customer?.title}
                        />
                        <Input
                            title={"Eingesetztes System"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"System"}
                            value={title}
                            onChange={onChangeTitleHandler}
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
                            onChange={onChangeCookieHandler}
                        />
                        <Input
                            title={"Trackingtool"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"Trackingtool"}
                            value={trackingsTools}
                            onChange={onChangeTrackingsToolsHandler}
                        />
                        <Input
                            title={"SSL-Zertifikat"}
                            inputType="text"
                            classList="mb-3"
                            placeholder={"SSL-Zertifikat"}
                            value={sslCertificate}
                            onChange={onChangeSslCertificateHandler}
                        />
                    </div>
                    <div className="mr-6">
                        <Select title={"Product manager auswählen"} classList={"mr-5 mb-3"} onChange={onChangeHandlerProductManager}>
                            <option value={""}>{"Product manager auswählen"}</option>
                            {
                                persons.map(person => {
                                    if (usedSystem?.productManager?.identifier == person.identifier) {
                                        return (
                                            <option key={person.identifier} selected value={person.identifier}>{person.firstName}{" "}{person.lastName}</option>
                                        )
                                    }
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
                                    if (usedSystem?.leadDev?.identifier == person.identifier) {
                                        return (
                                            <option key={person.identifier} selected value={person.identifier}>{person.firstName}{" "}{person.lastName}</option>
                                        )
                                    }
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
                        onChange={onChangeUrlPreviewHandler}
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

export default System;
