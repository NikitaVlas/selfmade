import React, {useCallback, useEffect} from 'react';
import AdminHeader from "../Utility/AdminHeader.tsx";
import LineForm from "../Utility/LineForm.tsx";
import {AppRootStateType, useAppSelector} from "../../Store/Store.ts";
import {useDispatch} from "react-redux";
import {SetProjectAC} from "../../Store/Reducers/projectReducer.ts";

function TroiProjects() {
    const projects = useAppSelector<AppRootStateType>(state => state.project)
    const dispatch = useDispatch();

    const fetchProjectsHandler = useCallback(async () => {
        try {
            const response = await fetch(`https://customer-dashboard.avency.dev/api/projects`);
            const data = await response.json();

            dispatch(SetProjectAC(data));
        } catch (error) {

        }
    }, [dispatch]);

    useEffect(() => {
        fetchProjectsHandler();
    }, [fetchProjectsHandler]);

    return (
        <div className="w-full">
            <AdminHeader
                headline={"Troi Projekten"}
            >
            </AdminHeader>
            <div className="mt-6 ml-12 mr-12">
                <div className="flex justify-between">
                    <div className="w-[170px]"><h6>Kundename</h6></div>
                    <div className="w-[200px]"><h6>Projectname</h6></div>
                    <div className="w-[80px]"><h6>ID</h6></div>
                    <div className="w-[80px]"><h6>PM</h6></div>
                </div>
                {projects.map(project => {
                    return <div key={project.identifier}>
                        <div className="flex justify-between">
                            <div className="w-[170px]">
                                {project.customer.title}
                            </div>
                            <div className="w-[200px]">
                                {project.projectName}
                            </div>
                            <div className="w-[80px]">
                                {project.projectNumber}
                            </div>
                            <div className="w-[80px]">
                                {project.projectLeader.abbreviationPerson}
                            </div>
                        </div>
                        <LineForm classList="mx-auto w-[100%] h-[2px] bg-gray-100 mt-3 mb-3"></LineForm>
                    </div>
                })}
            </div>
        </div>
    );
};

export default TroiProjects;
