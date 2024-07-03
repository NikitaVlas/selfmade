import React, {useCallback, useEffect} from 'react';
import AdminHeader from "../Utility/AdminHeader.tsx";
import Button from "../Utility/Button.tsx";
import {NavLink} from "react-router-dom";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faEye, faPencil} from "@fortawesome/free-solid-svg-icons";
import LineForm from "../Utility/LineForm.tsx";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";
import {useDispatch} from "react-redux";
import {SetUsedSystemAC} from "../../Store/Reducers/usedSystemReducer.ts";

function Systems() {
    const usedSystems = useAppSelector<AppRootStateType>(state => state.usedSystem)
    const dispatch = useDispatch();

    const fetchUsedSystemsHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/usedsystems`);
            const data = await response.json();

            dispatch(SetUsedSystemAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    useEffect(() => {
        fetchUsedSystemsHandler();
    }, [fetchUsedSystemsHandler]);

    return (
        <div className="w-full">
            <AdminHeader
                headline={"Verwendete Systeme"}
            >
            </AdminHeader>
            <div className="mt-6 ml-12 mr-12">
                <Button>
                    <NavLink
                        to={'/systemForm'}
                        className=''
                    >
                        {"neues System hinzufügen"}
                    </NavLink>
                </Button>
            </div>
            <div className="mt-6 ml-12 mr-12">
                <div className="flex justify-between">
                    <div className="w-[160px]"><h6>Kunde</h6></div>
                    <div className="w-[160px]"><h6>Systemname</h6></div>
                    <div className="w-[160px]"><h6>gebrauchte Version</h6></div>
                    <div className="w-[160px]"><h6>cookie</h6></div>
                    <div className="w-[160px]"><h6>Tracking-Tools</h6></div>
                    <div className="w-[160px]"><h6>SSL</h6></div>
                    <div className="w-[160px]"><h6>Live URL</h6></div>
                    <div className="w-[160px]"><h6>PM</h6></div>
                    <div className="w-[160px]"><h6>Lead Dev</h6></div>
                    <div className="w-[60px]"></div>
                </div>
                {usedSystems.map(usedSystem => {
                    return <div key={usedSystem.identifier}>
                        <div className="flex justify-between">
                            <div className="w-[160px]">
                                {usedSystem?.customer?.title}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem.title}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem.usedVersion}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem.cookie}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem.trackingsTools}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem.sslCertificate}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem.urlLive}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem?.productManager?.firstName}
                                {" "}
                                {usedSystem?.productManager?.lastName}
                            </div>
                            <div className="w-[160px]">
                                {usedSystem?.leadDev?.firstName}
                                {" "}
                                {usedSystem?.leadDev?.lastName}
                            </div>
                            <div className="flex justify-between w-[60px]">
                                <FontAwesomeIcon icon={faEye}/>
                                <NavLink
                                    to={"/usedsystem/" + usedSystem.identifier}
                                    className=''
                                >
                                    <FontAwesomeIcon icon={faPencil}/>
                                </NavLink>
                            </div>
                        </div>
                        <LineForm classList="mx-auto w-[100%] h-[2px] bg-gray-100 mt-3 mb-3"></LineForm>
                    </div>
                })}
            </div>
        </div>
    );
}

export default Systems;
